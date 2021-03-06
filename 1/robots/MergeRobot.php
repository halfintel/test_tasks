<?php
// можно добавить интерфейс для MergeRobot, если их будет несколько видов
namespace robots;
use robots\InterfaceRobot;

class MergeRobot implements InterfaceRobot {
    private $speed = null;
    private $weight = null;
    private $height = null;


    public function addRobot(array $robots) {// проверяем корректность данных
        foreach ($robots as $robot){// не нашёл, как проверить массив объектов интерфейса InterfaceRobot, поэтому сделал через цикл
            if ($robot instanceof InterfaceRobot !== true){
                throw new Exception('передан неверный объект');
            }
        }
        foreach ($robots as $robot){
            $this->setSpeed( $robot->getSpeed() );
            $this->setWeight( $robot->getWeight() );
            $this->setHeight( $robot->getHeight() );
        }
    }
    public function getSpeed() {
        return $this->speed;
    }
    public function getWeight() {
        return $this->weight;
    }
    public function getHeight() {
        return $this->height;
    }


    private function setSpeed($speed) {
        if ($this->speed === null){
            $this->speed = $speed;
        } else {
            $this->speed = min($this->speed, $speed);
        }
    }
    private function setWeight($weight) {
        if ($this->weight === null){
            $this->weight = $weight;
        } else {
            $this->weight = $this->weight + $weight;
        }
    }
    private function setHeight($height) {
        if ($this->height === null){
            $this->height = $height;
        } else {
            $this->height = $this->height + $height;
        }
    }
}
