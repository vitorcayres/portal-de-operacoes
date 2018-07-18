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
        
        # Parametros de Texto
        $this->_sistema = 'dashboard';
        $this->_titulo  = 'Bem Vindo ao Portal de Operações :: FS';
        $this->_subtitulo  = 'Dashboard';        
    }    

    public function __invoke(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/dashboard.phtml', [
            'pagina'            => 'dashboard',
            'menu_sistema'      => $this->_sistema,
            'titulo'            => $this->_titulo,
            'subtitulo'         => '' . $this->_subtitulo,
        ]);        
    }
}