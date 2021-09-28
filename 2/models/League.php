<?php
namespace models;

use models\Db;

class League {
    public function get($startTimestamp, $leagueId) {
        if ($leagueId !== null){
            return $this->getLeagueById($leagueId);
        }
        return $this->getLeagueByTimestamp($startTimestamp);
    }

    private function getLeagueById($leagueId) {
        $db = new Db();
        $data = $db->get(['league_id' => $leagueId]);
        return $data;
    }
    private function getLeagueByTimestamp($startTimestamp) {
        $db = new Db();
        $data = $db->get(['start_timestamp' => $startTimestamp]);
        return $data;
    }
}
