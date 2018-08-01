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
}