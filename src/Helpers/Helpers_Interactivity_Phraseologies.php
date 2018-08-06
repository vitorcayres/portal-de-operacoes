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
}