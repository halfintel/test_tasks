<?php
namespace views;

class RestApi {
    public function setResponse200($message) {
        $this->setResponse(200, $message);
    }
    public function setResponse404($message) {
        $this->setResponse(404, $message);
    }
    private function setResponse($code, $message) {
        $data = [
            'code' => $code,
            'message' => $message,
        ];
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
