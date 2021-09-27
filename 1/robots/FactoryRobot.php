<?php
namespace robots;
use robots\InterfaceRobot;

class FactoryRobot {
    private $robotPrototypeObjects = [];


    public function addType(InterfaceRobot $robot) {
        if ( isset( $this->robotPrototypeObjects[ get_class($robot) ] ) ){// для исключения ситуации, когда добавляется объект класса с тем же названием
            throw new Exception('этот тип робота уже сохранён');
        }
        $this->robotPrototypeObjects[ get_class($robot) ] = $robot;
    }
    // сделано так, чтобы не добавлять новые методы createRobot3, createRobot4 при добавлении новых типов роботов
    public function __call($name, $arguments) {
        $replaceText = 'create';
        $substr = substr($name, 0, 6);
        if ( $substr != $replaceText ){
            throw new Exception('этот метод отсутствует');
        }
        $countReplaces = 1;
        $robotType = str_replace($replaceText, '', $name, $countReplaces);
        $robotType = 'robots\\' . $robotType;
        if ( isset( $this->robotPrototypeObjects[ $robotType ] ) ){
            return $this->createRobots($robotType, $arguments[0]);
        }
        throw new Exception('этот тип робота отсутствует');
    }


    private function createRobots($robotType, $countRobots) {
        $robots = [];
        for ($i = 0; $i < $countRobots; $i++){
            $robots[] = clone $this->robotPrototypeObjects[ $robotType ];
        }
        return $robots;
    }
}
