<?php

namespace App\Helpers;

class Helpers_Interactivity_Partners
{
	
	/** 
	* Função: Tratativa da variavel para texto amigavel
	* Pagina: parceiros
	*/
	public function partnerTypeView($params) {
		switch ($params) {
			case 'CONTENT':
				return 'Conteúdo';
				break;
			case 'MEDIA':
				return 'Mídia';
				break;
			case 'MEDIA_AND_CONTENT':
				return 'Mídia/Conteúdo';
				break;								
		}
	}

	/** 
	* Função: Tratativa para retornar somente o id e o nome dos parceiros
	* Pagina: parceiros
	*/
	public function getNameAndIdPartners($params) {
		
		$params = ['data' => $params];				
        $data = [];
        foreach ($params as $v) {    

            $arr = array(
                'id' => $v->id,
                'name' => $v->name
            );

            $data[] = $arr;
        }
        return $data;
	}	
}