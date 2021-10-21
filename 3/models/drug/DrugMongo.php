<?php

namespace app\models\drug;

use Yii;
use yii\web\BadRequestHttpException;    // 400
use yii\web\UnauthorizedHttpException;  // 401
use yii\web\NotFoundHttpException;      // 404
use yii\web\ServerErrorHttpException;   // 500
use yii\mongodb\ActiveRecord;

class DrugMongo extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'drug';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'firm', 'region', 'city', 'date', 'delivery_address', 'legal_address', 'client', 'client_code', 'client_department_code', 'client_okpo', 'license', 'license_expiration_date', 'product_code', 'product_barcode', 'product', 'morion_code', 'unit_of_measure', 'manufacturer', 'provider', 'number_of_goods', 'warehouse'];
    }

    public function rules()
    {
        return [
            [['firm', 'region', 'city', 'date', 'delivery_address', 'legal_address', 'client', 'client_code', 'client_department_code', 'client_okpo', 'product_code', 'product', 'morion_code', 'unit_of_measure', 'manufacturer', 'provider', 'number_of_goods', 'warehouse'], 'required'],//TODO: вернуть license, 'license_expiration_date', 'product_barcode'
        ];
    }

    public function getPart($limit, $offset)
    {
        $data = self::find()
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        return $data;
    }
    
    public function set($drug)
    {//TODO: вернуть конвертацию для license, license_expiration_date и product_barcode, если это NOT NULL поля
        $drugModel = new self();
        $drugModel->firm = (string)$drug['firm'];
        $drugModel->region = (string)$drug['region'];
        $drugModel->city = (string)$drug['city'];
        $drugModel->date = (string)$drug['date'];//TODO: поменять на дату
        $drugModel->delivery_address = (string)$drug['delivery_address'];
        $drugModel->legal_address = (string)$drug['legal_address'];
        $drugModel->client = (string)$drug['client'];
        $drugModel->client_code = (int)$drug['client_code'];
        $drugModel->client_department_code = (string)$drug['client_department_code'];
        $drugModel->client_okpo = (int)$drug['client_okpo'];
        $drugModel->license = $drug['license'];
        $drugModel->license_expiration_date = $drug['license_expiration_date'];//TODO: поменять на дату
        $drugModel->product_code = (int)$drug['product_code'];
        $drugModel->product_barcode = $drug['product_barcode'];
        $drugModel->product = (string)$drug['product'];
        $drugModel->morion_code = (int)$drug['morion_code'];
        $drugModel->unit_of_measure = (string)$drug['unit_of_measure'];
        $drugModel->manufacturer = (string)$drug['manufacturer'];
        $drugModel->provider = (string)$drug['provider'];
        $drugModel->number_of_goods = (int)$drug['number_of_goods'];
        $drugModel->warehouse = (string)$drug['warehouse'];
        
        $isValid = $drugModel->validate();
        if (!$isValid) {
            Yii::warning([
                'message' => 'validate error',
                'isValid' => $isValid,
                'drug' => $drug,
                'model' => $drugModel->getAttributes(null),
            ], __METHOD__);
            throw new BadRequestHttpException('некорректные данные в таблице');
        }
        $drugModel->save();
    }

    //TODO: убрать, когда будет известно, как обеспечивается уникальность строк в таблице
    public function deleteAllData()
    {
        self::deleteAll([]);
    }
}
