<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\DM;
use App\Libraries\UserManagerPlatform;

class AjaxController
{
    
    public function getChannelsById(Request $request, Response $response, $args)
    {
        $args = $request->getParams();
        $id = $args['id'];

        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/channels?offerId='. $id);

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->id,
                'name' => $v->name
            ];
        }

        if(!empty($data)){
            return $response->withJson($data, 200)->withHeader('Content-type', 'application/json');
        }
    }

    public function getPartnersById(Request $request, Response $response, $args)
    {
        $args = $request->getParams();
        $id = $args['id'];

        $rows = UserManagerPlatform::GET(INTERACTIVITY_HOSTNAME, INTERACTIVITY_TOKEN, '/partners/'. $id);

        foreach ($rows->data as $v) {    
            $data[] = [
                'id' => $v->id,
                'name' => $v->name
            ];
        }

        if(!empty($data)){
            return $response->withJson($data, 200)->withHeader('Content-type', 'application/json');
        }
    }
    
    public function shorturl(Request $request, Response $response, $args)
    {

        if ($request->isPost() === false) {
            return false;
        }
    
        $args = $request->getParams();
        $url = $args['url'];

        $response = DM::shortUrl($args['url']);
        echo json_encode($response);
        return true;
    }
}