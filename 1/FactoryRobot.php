<?php

class FactoryRobot {
    private $types = [];


    function addType($robot) {
        $this->types[ get_class($robot) ] = $robot;
    }
    function createRobot($robotType, $countRobots) {
        $robots = [];
        for ($i = 0; $i < $countRobots; $i++){
            $robots[] = clone $this->types[ $robotType ];
        }
        return $robots;
    }
    public function __call($name, $arguments) {
        $robotType = str_replace('create', '', $name);
        if ( isset( $this->types[ $robotType ] ) ){
            return $this->createRobot($robotType, $arguments[0]);
        }
        throw new Exception('этот тип робота отсутствует');
    }
}
