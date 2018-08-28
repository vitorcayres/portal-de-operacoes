<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Products{

	/** 
	* Função: Recupera o parceiro por ID
	* Pagina: produtos
	*/
	public function productById($params) {

        // Recuperando os dados pelo id
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/products/' . $params);
        return $rows->name;
	}
}