<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;

class DashboardController
{

    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
    }    

    public function __invoke(Request $request, Response $response, $args){
        return $this->container->view->render($response, '/interface/dashboard.phtml');
    }
}