<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Luckynumber{

	/** 
	* Função: Tratativa para retornar somente o id e o nome dos tipos
	* Pagina: luckynumber
	*/
	public function getNameAndIdTypes($params) {

		unset($params->http_code);
		
        $data = [];
        foreach ($params as $k => $v) {    
            $arr = array(
                'id' => $k,
                'name' => $v
            );

            $data[] = $arr;
        }
        return $data;
	}
}