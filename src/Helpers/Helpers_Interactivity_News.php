<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_News
{
	
	/** 
	* Função: Recupera oferta por ID
	* Pagina: noticias
	*/
	public function offerById($params) {

        // Recuperando os dados pelo id
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/offers/' . $params);
        return $rows->name;
	}

	/** 
	* Função: Recupera canal por ID
	* Pagina: noticias
	*/
	public function channelById($params) {

        // Recuperando os dados pelo id
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/channels/' . $params);
        return $rows->name;
	}	

	/** 
	* Função: Status da noticia
	* Pagina: noticias
	*/
	public function statusNews($params) {
		switch ($params) {
			case 'PROCESSING':
				return '<span class="badge badge-primary">Processando</span>';
			break;
			case 'SCHEDULED':
				return '<span class="badge badge-info">Agendado</span>';
			break;
			case 'SENT':
				return '<span class="badge badge-success">Enviado</span>';
			break;
			case 'WAITING_APPROVAL':
				return '<span class="badge badge-danger">Aguardando Aprovação</span>';
			break;
			case 'PAUSED':
				return '<span class="badge badge-warning">Pausado</span>';
			break;
		}
	}  	
}