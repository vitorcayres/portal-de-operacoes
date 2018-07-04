<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \SlimSession\Helper;

class WorkplaceController
{

    private $_hostname = GDU_HOSTNAME;
    private $_token = GDU_TOKEN;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \SlimSession\Helper;
    }    

    public function list(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configurations/workplace/list.phtml', [
            'endpoint' => 'workplace',
            'gdu_hostname' => GDU_HOSTNAME,
            'gdu_token' => GDU_TOKEN,
            'token'    => $this->session->get('token'),
            'dataTablesColumns' => json_encode(array('id', 'name', 'create_date'))
        ]);
    }

    public function add(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configurations/workplace/add.phtml');
    }

    public function modify(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configurations/workplace/modify.phtml');
    }

    public function remove(Request $request, Response $response, $args)
    {
        
    }        

}