<?php

class MergeRobot {
    private $speed = null;
    private $weight = null;
    private $height = null;


    public function addRobot($robots) {
        if ( is_array($robots) ){
            foreach ($robots as $robot){
                $this->setSpeed( $robot->getSpeed() );
                $this->setWeight( $robot->getWeight() );
                $this->setHeight( $robot->getHeight() );
            }
        } else {
            $this->setSpeed( $robots->getSpeed() );
            $this->setWeight( $robots->getWeight() );
            $this->setHeight( $robots->getHeight() );
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
