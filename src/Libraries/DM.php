<?php

namespace App\Libraries;

use SoapClient;

class DM 
{
    const DM_URL = 'http://tim.whitelabel.com.br/dm-war/';

    public function shortUrl($url){
        try {
            $clientSoap = new SoapClient(self::DM_URL . 'URLCreatorWebService?WSDL', array('trace' => true));
            $req = array("originalUrl" => $url, "endOfLifeDate" => '', "campaign" => '');
            $response = $clientSoap->__soapCall('createShortUrl', array('parameters' => $req));

        }
        catch (SoapFault $e) {
            return array('result' => false, 'message' => $e->getMessage());
        }
        catch (Exception $e) {
            return array('result' => false, 'message' => $e->getMessage());
        }

        return array(
            'result' => true,
            'message' => str_replace("su.fsvas.com", 'promo.tv.br', $response->return)
        );
    }
}