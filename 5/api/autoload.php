<?php

function libraryOne($classname) {
    $classnameArr = explode('\\', $classname);
    $filename = '..';
    foreach ($classnameArr as $classnamePart){
        $filename .= '/' . $classnamePart;
    }
    $filename .= '.php';
    require_once($filename);
}

spl_autoload_register('libraryOne');