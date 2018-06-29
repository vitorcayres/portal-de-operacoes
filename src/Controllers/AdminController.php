<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AdminController
{

    public function __construct($container){
        $this->container = $container;
    }    

    public function execute(Request $request, Response $response, $args)
    {
        if(!empty($args))
        {
            $file = (!empty($args['params']))? '/interface/'. $args['params'] .'.phtml' : '/interface/error.phtml';
            $page = str_replace("/", "-", $args['params']);

            return $this->container->view->render($response, $file, [
            'gdu_hostname'  => GDU_HOSTNAME,
            'gdu_token'     => GDU_TOKEN,
            'page'          => $page
         ]);
        }
    }
}