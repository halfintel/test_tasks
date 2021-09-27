<meta charset="utf-8">
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', __DIR__ . '/logs/phpErrors__' . date("d-m-y") . '.log');
ini_set('log_errors', 1);


require_once('autoload.php');
require_once('debug.php');
use robots\FactoryRobot;
use robots\Robot1;
use robots\Robot2;
use robots\MergeRobot;


$main = new MainClass();
$main->mainFunc();


class MainClass {
    public function mainFunc() {
        $factory = new FactoryRobot();
        $factory->addType(new Robot1());
        $factory->addType(new Robot2());


        debug($factory->createRobot1(5));
        debug($factory->createRobot2(2));


        $mergeRobot = new MergeRobot();
        $mergeRobot->addRobot([new Robot2()]);
        $mergeRobot->addRobot($factory->createRobot2(2));

        $factory->addType($mergeRobot);
        $robots = $factory->createMergeRobot(1);
        $res = reset($robots);

        debug($res);
        debug( $res->getSpeed() );
        debug( $res->getWeight() );
        
    }
    
} 
