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
	
	/** 
	* Função: Tratativa para retornar somente o id e o nome dos parceiros
	* Pagina: parceiros
	*/
	public function getNameAndIdPartners($params) {
		
        $data = [];
        foreach ($params->data as $v) {    
            $arr = array(
                'id' => $v->id,
                'name' => $v->name
            );

            $data[] = $arr;
        }
        return $data;
	}

	/** 
	* Função: Tratativa para retornar somente o id e o nome das ofertas
	* Pagina: ofertas
	*/
	public function getNameAndIdOffers($params) {
		
        $data = [];
        foreach ($params->data as $v) {    
            $arr = array(
                'id' => $v->id,
                'name' => $v->name,
                'description' => $v->description
            );

            $data[] = $arr;
        }
        return $data;
	}	
}