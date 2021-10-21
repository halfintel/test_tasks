<?php

namespace app\models\drug;

use Yii;
use yii\web\BadRequestHttpException;    // 400
use yii\web\UnauthorizedHttpException;  // 401
use yii\web\NotFoundHttpException;      // 404
use yii\web\ServerErrorHttpException;   // 500
use yii\base\Model;
use app\models\drug\Drug;
use Elasticsearch\ClientBuilder;


class DrugElastic extends Model
{
    const INDEX = 'drug';
    const TYPE = '_doc';
    private $client;
    private $indexIsset;

    public function init()
    {
        parent::init();
        $this->client = ClientBuilder::create()->build();
        $indexParams['index'] = self::INDEX;
        $this->indexIsset = $this->client->indices()->exists($indexParams);
    }

    // перенос данных из mongoDB в Elasticsearch
    public function synch($drugs)
    {
        if (!$this->indexIsset){
            $this->create();
        }

        foreach ($drugs as $drug){
            $id = (string)$drug['_id'];
            $params = [
                'index' => self::INDEX,
                'type' => self::TYPE,
                'id' => $id,
                'body' => [
                    'firm' => $drug['firm'],
                    'region' => $drug['region'],
                    'city' => $drug['city'],
                    'product' => $drug['product'],
                    'number_of_goods' => $drug['number_of_goods'],
                ]
            ];
            $this->client->index($params);
        }

        return true;
    }
    
    // отчёт из Elasticsearch
    public function report()
    {
        if (!$this->indexIsset){
            $drug = new Drug();
            $drug->synch();
        }


        $params = [
            'index' => self::INDEX,
            'body' => [
                "size" => 0,
                'aggs' => [
                    'agg_region' => [
                        'terms' => [
                            'field' => 'region'
                        ],
                        'aggs' => [
                            'agg_product' => [
                                'terms' => [
                                    'field' => 'product'
                                ],
                                'aggs' => [
                                    'agg_sum' => [
                                        'sum' => [
                                            'field' => 'number_of_goods'
                                        ]
                                    ],
                                ]
                            ],
                        ]
                    ],
                ],
            ]
        ];
        $response = $this->client->search($params);


        $result = [];
        foreach ($response['aggregations']['agg_region']['buckets'] as $regionBucket){
            $region = $regionBucket['key'];
            foreach ($regionBucket['agg_product']['buckets'] as $productBucket){
                $product = $productBucket['key'];
                $sumNumberOfGoods = $productBucket['agg_sum']['value'];
                $result[] = [
                    'область' => $region,
                    'товар' => $product,
                    'количество' => $sumNumberOfGoods,
                ];
            }
        }


        return $result;
    }

    public function clearIndex()
    {
        if ($this->indexIsset){
            $params = ['index' => self::INDEX];
            $this->client->indices()->delete($params);
        }
        $this->create();
    }

    protected function create()
    {
        $params = [
            'index' => self::INDEX,
            'body' => [
                'mappings' => [
                    'properties' => [
                        'firm' => [
                            'type' => 'text'
                        ],
                        'region' => [
                            'type' => 'keyword'//для агрегации
                        ],
                        'city' => [
                            'type' => 'text'
                        ],
                        'product' => [
                            'type' => 'keyword'//для агрегации
                        ],
                        'number_of_goods' => [
                            'type' => 'integer'
                        ],
                    ]
                ]
            ]
        ];
        $response = $this->client->indices()->create($params);
        $this->indexIsset = true;
    }
}
