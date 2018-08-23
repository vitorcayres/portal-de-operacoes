<?php

namespace App\Helpers;

class Helpers_Interactivity_Phraseologies
{

	/** 
	* Função: Tratativa para retornar somente o uuid e o nome das campanhas
	* Pagina: campanhas
	*/
	public function getNameAndIdCampaign($params) {
		
        $data = [];
        foreach ($params->data as $v) {    
            $arr = array(
                'id' => $v->uuid,
                'name' => $v->name
            );

            $data[] = $arr;
        }
        return $data;
	}  	
	
	/** 
	* Função: Tratativa para retornar somente o id e o nome dos produtos
	* Pagina: produtos
	*/
	public function getNameAndIdProducts($params) {
		
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
	* Função: Tratativa para retornar somente o id e o nome dos tipos
	* Pagina: fraseologias
	*/
	public function getNameAndIdTypes($params) {

		unset($params->http_code);
			
        $data = [];
        foreach ($params as $k => $v) {    
            $arr = array(
                'id' => $k,
                'name' => $v->type,
                'brief_description' => $v->brief_description
            );

            $data[] = $arr;
        }
        return $data;
	}	

	/** 
	* Função: Tratativa para retornar o badge referente a operadora
	* Pagina: fraseologias
	*/
	public function badgeColorForCarrier($params) {
		
		switch ($params) {
			case 'VIVO':
			return '<span class="badge badge-primary">'. $params . '</span>';
				break;

			case 'TIM':
			return '<span class="badge badge-success">'. $params . '</span>';
				break;

			case 'OI':
			return '<span class="badge badge-warning">'. $params . '</span>';
				break;

			case 'NEXTEL':
			return '<span class="badge badge-info">'. $params . '</span>';
				break;

			case 'CLARO':
			return '<span class="badge badge-danger">'. $params . '</span>';
				break;
		}
	}

}