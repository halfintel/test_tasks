<?php
// TODO: додати set_error_handler
header("Access-Control-Allow-Origin: *");
require_once('../config.php');
if (DEV_MODE){
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/phpErrors__' . date("d-m-y") . '.log');
    ini_set('log_errors', 1);
}


require_once('../autoload.php');
use controllers\Projects;
use views\RestApi;

$main = new MainClass();
$main->router();


class MainClass {
    public function router() {
        $restApi = new RestApi();
        $path = $_GET['path'] ?? '';
        $pathArr = explode('/', $path);
        $countPathArr = count($pathArr);
        $message = $pathArr;
        if ($countPathArr == 1 && $pathArr[0] == 'projects'){// /?path=projects
            $projects = new Projects();
            $projects->all();
            
        } else if ($countPathArr == 1 && $pathArr[0] == 'parse'){// /?path=parse
            $projects = new Projects();
            $projects->parse();
            
        } else {
            $restApi->setResponse404('данная страница не найдена');
        }
    }
} 
