<?php
// метод логирования
function debug($arr1 = null, $arr2 = null) {
    echo '<pre>';

    print_r($arr1);
    print_r($arr2);

    //var_dump($arr1);
    //var_dump($arr2);

    echo '</pre>';
}
