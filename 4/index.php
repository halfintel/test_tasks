<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', __DIR__ . '/logs/phpErrors__' . date("d-m-y") . '.log');
ini_set('log_errors', 1);
header('Content-Type: text/html; charset=utf-8');


require_once('Converter.php');
require_once('debug.php');


$tests = [
    [
        'number' => 0,
        'result' => 'число меньше 1',
        'type' => 'exception'
    ],
    [
        'number' => 1,
        'result' => 'A0001',
        'type' => 'correct'
    ],
    [
        'number' => 2,
        'result' => 'A0002',
        'type' => 'correct'
    ],
    [
        'number' => 9999,
        'result' => 'A9999',
        'type' => 'correct'
    ],
    [
        'number' => 10000,
        'result' => 'B0000',
        'type' => 'correct'
    ],
    [
        'number' => 19999,
        'result' => 'B9999',
        'type' => 'correct'
    ],
    [
        'number' => 20000,
        'result' => 'C0000',
        'type' => 'correct'
    ],
    [
        'number' => 250000,
        'result' => 'Z0000',
        'type' => 'correct'
    ],
    [
        'number' => 259999,
        'result' => 'Z9999',
        'type' => 'correct'
    ],
    [
        'number' => 'text',
        'result' => 'получено не число',
        'type' => 'exception'
    ],
    [
        'number' => '0',
        'result' => 'получено не число',
        'type' => 'exception'
    ],
    [
        'number' => 260000,
        'result' => 'ZA000',
        'type' => 'correct'
    ],
    [
        'number' => 260999,
        'result' => 'ZA999',
        'type' => 'correct'
    ],
    [
        'number' => 261000,
        'result' => 'ZB000',
        'type' => 'correct'
    ],
    [
        'number' => 285999,
        'result' => 'ZZ999',
        'type' => 'correct'
    ],
    [
        'number' => 286000,
        'result' => 'ZZA00',
        'type' => 'correct'
    ],
    [
        'number' => 288599,
        'result' => 'ZZZ99',
        'type' => 'correct'
    ],
    [
        'number' => 288600,
        'result' => 'ZZZA0',
        'type' => 'correct'
    ],
    [
        'number' => 288859,
        'result' => 'ZZZZ9',
        'type' => 'correct'
    ],
    [
        'number' => 288860,
        'result' => 'ZZZZA',
        'type' => 'correct'
    ],
    [
        'number' => 288885,
        'result' => 'ZZZZZ',
        'type' => 'correct'
    ],
    [
        'number' => 288886,
        'result' => 'число больше максимально допустимого',
        'type' => 'exception'
    ],
];


foreach ($tests as $test){
    debug('---');
    try {
        $converter = new Converter($test['number']);
        $result = $converter->getCode();
        $type = 'correct';
    } catch (Exception $e) {
        $result = $e->getMessage();
        $type = 'exception';
    }
    
    
    if ($result !== $test['result'] || $type !== $test['type']){
        debug('test: ' . $test['number']);
        debug('testResult: ' . $test['result']);
        debug('result: ' . $result);
        debug('testType: ' . $test['type']);
        debug('type: ' . $type);
        throw new Exception('Тесты не пройдены');
    }
}
debug('Тесты пройдены');
