<?php

namespace app\models\drug;

use Yii;
use yii\web\BadRequestHttpException;    // 400
use yii\web\UnauthorizedHttpException;  // 401
use yii\web\NotFoundHttpException;      // 404
use yii\web\ServerErrorHttpException;   // 500
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use app\models\drug\DrugMongo;
use app\models\drug\DrugElastic;

class DrugFacade extends Model
{
    const FILENAME = '../data/file.xls';

    // перенос данных из FILENAME в DrugMongo
    public function parse()
    {
        if (!file_exists(self::FILENAME)) {
            throw new BadRequestHttpException('файл не найден');
        }
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(self::FILENAME);
        $table = $spreadsheet->getActiveSheet()->toArray();
        $data = [];
        $drugModel = new DrugMongo();
        $drugModel->deleteAllData();//TODO: убрать, когда будет известно, как обеспечивается уникальность строк в таблице DrugMongo
        $lastRowInd = count($table) - 1;
        $this->checkParseTable($table);
        foreach ($table as $ind => $row){
            if ($ind == 0 || $ind == $lastRowInd){
                continue;
            }
            $data[] = $row;
            $drug = [
                'firm' => $row[0], 
                'region' => $row[1], 
                'city' => $row[2], 
                'date' => $row[3], 
                'delivery_address' => $row[4], 
                'legal_address' => $row[5], 
                'client' => $row[6], 
                'client_code' => $row[7], 
                'client_department_code' => $row[8], 
                'client_okpo' => $row[9], 
                'license' => $row[10], 
                'license_expiration_date' => $row[11], 
                'product_code' => $row[12], 
                'product_barcode' => $row[13], 
                'product' => $row[14], 
                'morion_code' => $row[15], 
                'unit_of_measure' => $row[16], 
                'manufacturer' => $row[17], 
                'provider' => $row[18], 
                'number_of_goods' => $row[19], 
                'warehouse' => $row[20]
            ];
            $drugModel->set($drug);
        }
        
        return (count($table) - 2);
    }

    // перенос данных из DrugMongo в DrugElastic
    public function synch()
    {
        $drugElastic = new DrugElastic();
        $drugMongo = new DrugMongo();
        $drugElastic->clearIndex();
        $limit = 1000;
        $offset = 0;
        $countDrugs = $limit;
        $countSynchronizedDrugs = 0;
        while ($countDrugs === $limit){
            $drugs = $drugMongo->getPart($limit, $offset);
            $offset = $offset + $limit;
            $countDrugs = count($drugs);
            $countSynchronizedDrugs += $countDrugs;
            $drugElastic->synch($drugs);
        }
        return $countSynchronizedDrugs;
    }
    
    // отчёт из DrugElastic
    public function report()
    {
        $drugElastic = new DrugElastic();
        return $drugElastic->report();
    }

    // проверка корректности данных в FILENAME
    protected function checkParseTable($table)
    {
        $firstLine = ['Фирма', 'Область', 'Город', 'Дата накл', 'Факт.адрес доставки', 'Юр. адрес клиента', 'Клиент', 'Код клиента', 'Код подразд кл', 'ОКПО клиента', 'Лицензия', 'Дата окончания лицензии', 'Код товара', 'Штрих-код товара', 'Товар', 'Код мориона', 'ЕИ', 'Производитель', 'Поставщик', 'Количество', 'Склад/филиал'];
        if ($table[0] !== $firstLine){
            throw new BadRequestHttpException('некорректные данные в таблице');
        }
        // TODO: добавить проверку содержимого таблицы
    }
}
