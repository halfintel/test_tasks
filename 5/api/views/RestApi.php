<?php
namespace views;

class RestApi {
    public static function setResponse200($message) {
        self::setResponse(200, $message);
    }
    public static function setResponse400($message) {
        self::setResponse(400, $message);
    }
    public static function setResponse404($message) {
        self::setResponse(404, $message);
    }
    public static function setResponse500($message) {
        self::setResponse(500, $message);
    }
    private static function setResponse($code, $message) {
        $data = [
            'code' => $code,
            'message' => $message,
        ];
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }
}
