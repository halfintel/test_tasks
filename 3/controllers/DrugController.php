<?php

namespace app\controllers;

use Yii;
use yii\web\BadRequestHttpException;    // 400
use yii\web\UnauthorizedHttpException;  // 401
use yii\web\NotFoundHttpException;      // 404
use yii\web\ServerErrorHttpException;   // 500
use yii\web\Controller;
use yii\web\Response;
use app\models\drug\DrugFacade;
use app\models\drug\DrugElastic;

class DrugController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    // перенос данных из файла в mongoDB
    public function actionParse()
    {
        $drug = new DrugFacade();
        $countParsedRows = $drug->parse();
        return $this->makeResponse('all data parsed (' . $countParsedRows . ' rows)');
    }

    // перенос данных из mongoDB в Elasticsearch
    public function actionSynch()
    {
        $drug = new DrugFacade();
        $countSynchronizedDrugs = $drug->synch();

        return $this->makeResponse('all data synchronized (' . $countSynchronizedDrugs . ' rows)');
    }

    // отчёт из Elasticsearch
    public function actionReport()
    {
        $drug = new DrugFacade();
        $report = $drug->report();

        return $this->makeResponse($report);
    }

    protected function makeResponse($data = null)
    {
        $response = [
            'name' => 'Success',
            'message' => $data,
            'code' => 0,
            'status' => 200,
            'type' => 'Success',
        ];
        return $response;
    }
}
