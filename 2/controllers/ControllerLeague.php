<?php
namespace controllers;

use models\League;
use models\Dota2Api;
use models\Db;
use views\RestApi;

class ControllerLeague {
    // запрос данных из БД
    public function get($startTimestamp, $leagueId = null) {
        $league = new League();
        $restApi = new RestApi();
        $leagues = $league->get($startTimestamp, $leagueId);
        $restApi->setResponse200($leagues);
    }
    // очистка таблицы и запись данных из https://www.dota2.com/webapi/IDOTA2League/GetLeagueInfoList/v001
    public function parse() {
        $dota2Api = new Dota2Api();
        $db = new Db();
        $restApi = new RestApi();
        $data = $dota2Api->get();
        $db->set($data);
        $restApi->setResponse200('all data parsed');
    }
}
