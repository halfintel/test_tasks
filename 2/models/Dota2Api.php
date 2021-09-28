<?php
namespace models;

use models\Curl;

class Dota2Api {
    public function get() {
        $curl = new Curl();
        $data = $curl->get('https://www.dota2.com/webapi/IDOTA2League/GetLeagueInfoList/v001');
        $dataJson = json_decode($data, true);
        if (isset($dataJson['infos'])){
            return $dataJson['infos'];
        }
        throw new \Exception('получены некорректные данные');
    }
}
