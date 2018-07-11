<?php

namespace App\Controllers\Configurations;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;

class WorkplaceController
{
    private $_hostname = GDU_HOSTNAME;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema = 'configuracoes';
        $this->_subtitulo  = 'Empresa';        
        $this->_titulo  = 'Configurações :: ' . $this->_subtitulo;
        $this->_pagina = 'empresas';
        $this->_endpoint = 'workplace';
        $this->_template = '/interface/configuracoes/empresas';

    }    

    public function listar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, $this->_template . '/listar.phtml', [
            'endpoint'          => $this->_endpoint,
            'pagina'            => $this->_pagina,
            'menu_sistema'      => $this->_sistema,
            'titulo'            => $this->_titulo,
            'subtitulo'         => 'Listar ' . $this->_subtitulo,
            'hostname'          => $this->_hostname,
            'token'             => $this->session->get('token'),
            'dataTablesColumns' => 'id, name'
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::POST($this->_hostname, $this->session->get('token'), '/'. $this->_endpoint, $body);

            switch ($rows->status) {
                case 'success':
                    $this->container->flash->addMessage('success', $rows->message);
                    return $response->withStatus(200)->withHeader('Location', 'listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', $rows->message);
                    return $response->withStatus(400)->withHeader('Location', 'inserir');                
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/inserir.phtml', [
            'endpoint'      => $this->_endpoint,
            'pagina'        => $this->_pagina,
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Nova '. $this->_subtitulo,                     
            'hostname'      => $this->_hostname,
            'token'         => $this->session->get('token')       
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->session->get('token'), '/'. $this->_endpoint . '/' . $id);
        
        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::PUT($this->_hostname, $this->session->get('token'), '/'. $this->_endpoint .'/'. $id, $body);

            switch ($rows->status) {
                case 'success':
                    $this->container->flash->addMessage('success', $rows->message);
                    return $response->withStatus(200)->withHeader('Location', '../listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', $rows->message);
                    return $response->withStatus(400)->withHeader('Location', '../editar/'. $id);
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/editar.phtml', [
            'endpoint'      => $this->_endpoint,
            'pagina'        => $this->_pagina,
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Editar '. $this->_subtitulo,           
            'hostname'      => $this->_hostname,
            'token'         => $this->session->get('token'),
            'id'            => $args['id'],
            'rows'          => $rows->data,
            'menu_sistema'  => 'configuracoes'        
        ]);        
    }

    public function remover(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];

        $rows = UserManagerPlatform::DELETE($this->_hostname, $this->session->get('token'), '/'. $this->_endpoint .'/', $id);

        switch ($rows->status) {
            case 'success':
                return true;
                break;
            
            default:
                $this->container->flash->addMessage('error', $rows->message);
                return $response->withStatus(400)->withHeader('Location', '../listar');
                break;
        }       
    }        

}