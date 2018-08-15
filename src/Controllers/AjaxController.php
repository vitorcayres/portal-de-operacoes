<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Helpers\Helpers_Interactivity_Channels;
use App\Helpers\Helpers_Interactivity_Partners;

class AjaxController
{
    
    public function getChannelsById(Request $request, Response $response, $args)
    {
        $args = $request->getParams();
        $id = $args['id'];

        $canais = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/channels?offerId='. $id);
        $canais = (object) Helpers_Interactivity_Channels::getNameAndIdChannels($canais); 
        
        if(!empty($canais)){
            return $response->withJson($canais, 200)->withHeader('Content-type', 'application/json');
        }
    }

    public function getPartnersById(Request $request, Response $response, $args)
    {
        $args = $request->getParams();
        $id = $args['id'];

        $parceiros = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/partners/'. $id);
        $parceiros = (object) Helpers_Interactivity_Partners::getNameAndIdPartners($parceiros); 
        
        if(!empty($parceiros)){
            return $response->withJson($parceiros, 200)->withHeader('Content-type', 'application/json');
        }
    }
}