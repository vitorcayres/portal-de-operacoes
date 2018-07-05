<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \SlimSession\Helper;
use App\Libraries\UserManagerPlatform;

class WorkplaceController
{
    private $_hostname = GDU_HOSTNAME;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \SlimSession\Helper;
    }    

    public function listar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configuracoes/empresas/listar.phtml', [
            'endpoint'      => 'workplace',
            'pagina'        => 'empresas',
            'hostname'      => $this->_hostname,
            'token'         => $this->session->get('token'),
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
                    
                    # Message
                    $this->container->flash->addMessage('success', 'Registro inserido com sucesso.');
                    return $response->withStatus(200)->withHeader('Location', 'listar');
                    
                    break;
                
                default:
                    $this->container->flash->addMessage('error', $rows->message);
                    break;
            }
        }

        return $this->container->view->render($response, '/interface/configuracoes/empresas/inserir.phtml', [
            'endpoint'      => 'workplace',
            'pagina'        => 'empresas',            
            'hostname'      => $this->_hostname,
            'token'         => $this->session->get('token')
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/configuracoes/empresas/editar.phtml');
    }

    public function remover(Request $request, Response $response, $args)
    {
        
    }        

}