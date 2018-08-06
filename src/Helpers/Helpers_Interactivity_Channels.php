<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Channels
{
	
	/** 
	* Função: Recupera oferta por ID
	* Pagina: canais
	*/
	public function offerById($params) {

        // Recuperando os dados pelo id
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/offers/' . $params);
        return $rows->name;
	}

	/** 
	* Função: Status da oferta
	* Pagina: canais
	*/
	public function statusOffers($params) {
		switch ($params) {
			case 'true':
				return '<span class="badge badge-primary">Ativo</span>';
				break;
			default:
				return '<span class="badge badge-danger">Inativo</span>';
				break;
		}
	}  	
}