<?php

namespace App\Helpers;

class Helpers_Interactivity_Phraseologies
{
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