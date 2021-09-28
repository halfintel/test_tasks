<?php
namespace models;

class Db {
    private $pdo = null;


    // очистка таблицы и запись данных
    public function set(array $data) {
        //TODO: добавить проверку данных
        //TODO: объединить в один запрос
        $stmt = $this->pdo->prepare("TRUNCATE TABLE leagues.leagues;");
        $stmt->execute();
        foreach ($data as $item){
            $stmt = $this->pdo->prepare("INSERT INTO leagues (league_id, league_name, start_timestamp) VALUES (:league_id, :league_name, :start_timestamp);");
            $params = [
                ':league_id' => (int)$item['league_id'],
                ':league_name' => (string)$item['name'],
                ':start_timestamp' => (int)$item['start_timestamp'],
            ];
            $stmt->execute($params);
        }
    }
    // запрос данных, $filters = ['league_id' => $league_id, 'start_timestamp' => $start_timestamp]
    public function get(array $filters = []) {
        if (isset($filters['league_id'])){
            $stmt = $this->pdo->prepare("SELECT * FROM leagues WHERE `league_id` = ?");
            $stmt->execute([$filters['league_id']]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } else if (isset($filters['start_timestamp'])){
            $stmt = $this->pdo->prepare("SELECT * FROM leagues WHERE `start_timestamp` >= ?");
            $stmt->execute([$filters['start_timestamp']]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM leagues");
            $stmt->execute([]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }
    //TODO: добавить метод создания таблицы
    function __construct() {
        $dbHost = 'localhost';
        $dbName = 'leagues';
        $dbUser = 'root';
        $dbPassword = '';
        $this->pdo = new \PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
    }
}
