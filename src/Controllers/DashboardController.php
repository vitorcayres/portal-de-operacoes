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
        $this->_sistema     = 'dashboard';
        $this->_titulo      = 'Bem Vindo ao Portal de Operações :: FS';
        $this->_subtitulo   = 'Dashboard';
        $this->_pagina      = 'dashboard';

        # Permissões 
        $this->_permissoes = [
            'interface' => 'dashboard'
        ];

    }    

    public function __invoke(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/dashboard.phtml', [
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Listar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,    
            'menu_dashboard'            => 'class=active'
        ]);
    }
}