<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Libraries\UserManagerPlatform;
use \SlimSession\Helper;

class DashboardController
{

    public function __construct($container){
        $this->container = $container;
        $this->session = new \SlimSession\Helper;
    }    

    public function __invoke(Request $request, Response $response, $args){
        return $this->container->view->render($response, '/interface/dashboard.phtml');
    }
}