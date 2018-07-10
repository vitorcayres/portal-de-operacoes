<?php

namespace App\Controllers;

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
        $this->_titulo  = 'Configurações :: Empresas';
        $this->_subtitulo  = 'Empresas';

    }    

    public function listar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configuracoes/empresas/listar.phtml', [
            'endpoint'          => 'workplace',
            'pagina'            => 'empresas',
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
        if($request->isPost()){

            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::POST($this->_hostname, $this->session->get('token'), '/workplace', $body);

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

        return $this->container->view->render($response, '/interface/configuracoes/empresas/inserir.phtml', [
            'endpoint'      => 'workplace',
            'pagina'        => 'empresas',
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Nova Empresa',                     
            'hostname'      => $this->_hostname,
            'token'         => $this->session->get('token'),
            'menu_sistema'  => 'configuracoes'          
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->session->get('token'), '/workplace/'. $id);

        if($request->isPost()){

            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::PUT($this->_hostname, $this->session->get('token'), '/workplace/'. $id, $body);

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

        return $this->container->view->render($response, '/interface/configuracoes/empresas/editar.phtml', [
            'endpoint'      => 'workplace',
            'pagina'        => 'empresas',
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Editar Empresa',           
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

        $rows = UserManagerPlatform::DELETE($this->_hostname, $this->session->get('token'), '/workplace/', $id);

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