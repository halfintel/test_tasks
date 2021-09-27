<?php
namespace robots;
use robots\InterfaceRobot;

class Robot2 implements InterfaceRobot {
    private $speed = 20;
    private $weight = 20;
    private $height = 20;


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
