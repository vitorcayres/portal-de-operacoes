<?php

namespace App\Libraries;

class UserManagerPlatform {

    public function GET($hostname, $token, $route){

        $url = $hostname . $route;

        $header = array(
            'Content-Type:application/json',
            'Authorization: ' . $token
        );
      
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                                   
         
        $result = curl_exec($ch);

        return json_decode($result);

    }

    public function POST($hostname, $token, $route, $data) {

        $url = $hostname . $route;

        $header = array(
            'Content-Type:application/json',
            'Authorization: ' . $token
        );
       
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                                   
         
        $result = curl_exec($ch);

        return json_decode($result);
    }

}