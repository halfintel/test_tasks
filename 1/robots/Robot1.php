<?php
namespace robots;
use robots\InterfaceRobot;

class Robot1 implements InterfaceRobot {
    private $speed = 10;
    private $weight = 10;
    private $height = 10;


    function getSpeed() {
        return $this->speed;
    }
    function getWeight() {
        return $this->weight;
    }
    function getHeight() {
        return $this->height;
    }
}
