<?php
namespace models;

class Db {
    private $pdo = null;


    public function execute(string $prepare, array $params = []) {
        $stmt = $this->pdo->prepare($prepare);
        $stmt->execute($params);
    }
    
    public function getAll(string $prepare, array $params = []) {
        $stmt = $this->pdo->prepare($prepare);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    function __construct() {
        $this->pdo = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
