<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Channels
{
	public function offerById($params) {
        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/offers/' . $params);
        return $rows->name;
	}

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