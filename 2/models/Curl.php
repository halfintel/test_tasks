<?php
namespace models;

class Curl {
    public function get($url) {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
