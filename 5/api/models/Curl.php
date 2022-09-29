<?php
namespace models;

class Curl {
    public function get($url) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $authorization = "Authorization: Bearer " . FREELANCEHUNT_API_TOKEN;// TODO: винести у параметри
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
