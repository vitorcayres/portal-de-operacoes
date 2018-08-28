<?php

namespace App\Helpers;

use App\Libraries\UserManagerPlatform;

class Helpers_Interactivity_Filters
{
	public function filterPartners() {
		
		$rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/partners?limit=1000');

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->id,
                'name' => $v->name
            ];
        }
        return $data;
	}

	public function filterOffers() {
	
		$rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/offers?limit=1000');

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' 			=> $v->id,
                'name' 			=> $v->name,
                'description' 	=> $v->description,
                'partner_id' 	=> $v->partner->id
            ];
        }
        return $data;
	}	

	public function filterChannels() {
	
	    $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/channels?limit=1000');

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->id,
                'name' => $v->name
            ];
        }
        return $data;
	}

	public function filterCampaigns() {

	    $rows = UserManagerPlatform::GET(CAMPAIGN_HOSTNAME, CAMPAIGN_TOKEN, '/getcampaigns');

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->uuid,
                'name' => $v->name
            ];
        }
        return $data;
	}

	public function filterProducts() {

	    $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/products?limit=1000');           

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->id,
                'name' => $v->name
            ];
        }
        return $data;
	}

	public function filterTypes() {

	    $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/phraseologies/all-types');  
		unset($rows->http_code);
			
        $data = [];
        foreach ($rows as $k => $v) {    
            $data[] = [
                'id' => $k,
                'name' => $v->type,
                'brief_description' => $v->description,
                'description' => $v->brief_description
            ];
        }
        return $data;
	}	

}