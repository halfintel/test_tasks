<meta charset="utf-8">
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', __DIR__ . '/logs/phpErrors__' . date("d-m-y") . '.log');
ini_set('log_errors', 1);


require_once('FactoryRobot.php');
require_once('MergeRobot.php');
require_once('Robot1.php');
require_once('Robot2.php');


$main = new MainClass();
$main->mainFunc();


class MainClass {
    public function mainFunc() {
        $factory = new FactoryRobot();
        $factory->addType(new Robot1());
        $factory->addType(new Robot2());


        $this->output($factory->createRobot1(5));
        $this->output($factory->createRobot2(2));


        $mergeRobot = new MergeRobot();
        $mergeRobot->addRobot(new Robot2());
        $mergeRobot->addRobot($factory->createRobot2(2));

        $factory->addType($mergeRobot);
        $robots = $factory->createMergeRobot(1);
        $res = reset($robots);

        $this->output($res);
        $this->output( $res->getSpeed() );
        $this->output( $res->getWeight() );
        
    }
    function output($arr1 = null, $arr2 = null) {
        echo '<pre>';
        print_r($arr1);
        print_r($arr2);
        echo '</pre>';
    }
} 
