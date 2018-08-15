<?php

namespace App\Libraries;

class UserManagerPlatform {

    public function GET($hostname, $token, $route)
    {
        $url = $hostname . $route;

        $header = array(
            'Content-Type:application/json',
            'Authorization: ' . $token
        );
      
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);        
        $body = curl_exec($ch);
        $httpcode = curl_getinfo($ch);        
        curl_close($ch);
        $result = (array) json_decode($body);
        $result = (object) array_merge($result, ['http_code' => $httpcode['http_code']]);

        return $result;
    }

    public function POST($hostname, $token, $route, $data)
    {
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);        
        $body = curl_exec($ch);
        $httpcode = curl_getinfo($ch);        
        curl_close($ch);
        $result = (array) json_decode($body);
        $result = (object) array_merge($result, ['http_code' => $httpcode['http_code']]);

        return $result;
    }

    public function PUT($hostname, $token, $route, $data)
    {
        $url = $hostname . $route;

        $header = array(
            'Content-Type:application/json',
            'Authorization: ' . $token
        );
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        $body = curl_exec($ch);
        $httpcode = curl_getinfo($ch);        
        curl_close($ch);
        $result = (array) json_decode($body);
        $result = (object) array_merge($result, ['http_code' => $httpcode['http_code']]);

        return $result;
    }

    public function DELETE($hostname, $token, $route, $id)
    {
        $url = $hostname . $route . $id;

        $header = array(
            'Content-Type:application/json',
            'Authorization: ' . $token
        );
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $body = curl_exec($ch);
        $httpcode = curl_getinfo($ch);        
        curl_close($ch);
        $result = (array) json_decode($body);
        $result = (object) array_merge($result, ['http_code' => $httpcode['http_code']]);

        return $result;
    }

}