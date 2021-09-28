<?php
//TODO: добавить JWT
//TODO: добавить set_error_handler

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', __DIR__ . '/logs/phpErrors__' . date("d-m-y") . '.log');
ini_set('log_errors', 1);


require_once('autoload.php');
use controllers\ControllerLeague;
use views\RestApi;

$main = new MainClass();
$main->router();

class MainClass {
    public function router() {
        $restApi = new RestApi();
        $path = $_GET['path'] ?? '';
        $pathArr = explode('/', $path);
        $countPathArr = count($pathArr);
        if ($countPathArr == 1 && $pathArr[0] == 'parse'){// ./parse
            $league = new ControllerLeague();
            $league->parse();
            
        } else if ($countPathArr == 1 && $pathArr[0] == 'leagues'){// ./leagues?start_timestamp={timestamp}
            $league = new ControllerLeague();
            $startTimestamp = $_GET['start_timestamp'] ?? 0;
            $startTimestamp = (int)$startTimestamp;
            $leagues = $league->get($startTimestamp);

        } else if ($countPathArr == 2 && $pathArr[0] == 'leagues'){// ./leagues/{league_id}
            $league = new ControllerLeague();
            $startTimestamp = 0;
            $leagueId = (int)$pathArr[1];
            $leagues = $league->get($startTimestamp, $leagueId);

        } else {
            $restApi->setResponse404('данная страница не найдена');
        }
    }
} 
