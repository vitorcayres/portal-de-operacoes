<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Offers{

	/** 
	* Função: Recupera o parceiro por ID
	* Pagina: ofertas
	*/
	public function partnerById($params) {

        // Recuperando os dados pelo id
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/partners/' . $params);
        return $rows->name;
	}  

	/** 
	* Função: Visibilidade da oferta
	* Pagina: ofertas
	*/
	public function visible($params) {
		switch ($params) {
			case 'true':
				return '<span class="badge badge-primary">Publico</span>';
				break;
			default:
				return '<span class="badge badge-danger">Privado</span>';
				break;
		}
	}  	
}