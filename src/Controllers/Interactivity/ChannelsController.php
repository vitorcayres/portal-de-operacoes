<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;

class ChannelsController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Ofertas';
        $this->_subtitulo   = 'Oferta';
        $this->_pagina      = 'ofertas';
        $this->_template    = '/interface/interatividade/ofertas';

        # Variaveis de ambiente
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'offers';

        # Permissões 
        $this->_permissions = [
            'listar'    => 'listar-oferta',
            'inserir'   => 'inserir-oferta',
            'editar'    => 'editar-oferta',
            'remover'   => 'remover-oferta'
        ];         
    }    

    public function listar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, $this->_template . '/listar.phtml', [
            'endpoint'          => $this->_endpoint,
            'pagina'            => $this->_pagina,
            'menu_sistema'      => $this->_sistema,
            'titulo'            => $this->_titulo,
            'subtitulo'         => 'Listar ' . $this->_subtitulo,
            'sessao'            => $this->session,            
            'hostname'          => $this->_hostname,
            'token'             => $this->_token,
            'permissoes'        => $this->_permissions            
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::POST($this->_hostname, $this->_token, '/'. $this->_endpoint, $body);

            switch ($rows->status) {
                case 'success':
                    $this->container->flash->addMessage('success', $rows->message);
                    return $response->withStatus(200)->withHeader('Location', 'listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', $rows->message);
                    return $response->withHeader('Location', 'inserir');                
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/inserir.phtml', [
            'endpoint'      => $this->_endpoint,
            'pagina'        => $this->_pagina,
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Novo '. $this->_subtitulo,
            'sessao'        => $this->session,                             
            'hostname'      => $this->_hostname,
            'token'         => $this->_token       
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);

        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $rows = UserManagerPlatform::PUT($this->_hostname, $this->_token, '/'. $this->_endpoint .'/'. $id, $body);

            switch ($rows->status) {
                case 'success':
                    $this->container->flash->addMessage('success', $rows->message);
                    return $response->withStatus(200)->withHeader('Location', '../listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', $rows->message);
                    return $response->withHeader('Location', '../editar/'. $id);
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/editar.phtml', [
            'endpoint'      => $this->_endpoint,
            'pagina'        => $this->_pagina,
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Editar '. $this->_subtitulo,
            'sessao'        => $this->session,                    
            'hostname'      => $this->_hostname,
            'token'         => $this->_token,
            'id'            => $args['id'],
            'rows'          => $rows      
        ]);        
    }

    public function remover(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];

        $rows = UserManagerPlatform::DELETE($this->_hostname, $this->_token, '/'. $this->_endpoint .'/', $id);

        switch ($rows->status) {
            case 'success':
                return $response->withJson($rows, 200)
                ->withHeader('Content-type', 'application/json');  
                break;
            
            default:
                return $response->withJson($rows, 400)
                ->withHeader('Content-type', 'application/json');  
                break;
        }     
    }

    public function loadTable(Request $request, Response $response, $args)
    {
        $request = $request->getParams();

        $start = ($request['start'] == 0)? 1 : $request['start'];
        $length = ($request['length'] == 0)? 1 : $request['length'];
        $page = (int)($start / $request['length']) + 1;

        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint .'?page='. $page . '&limit='. $length);
        $data = [];

        foreach ($rows->data as $v) {
            $arr   = [];
            $arr[] = $v->id;
            $arr[] = $v->name;
            $arr[] = $v->description;            
            $arr[] = Helpers_Interactivity_Offers::partnerById($v->partner->id);
            $arr[] = Helpers_Interactivity_Offers::visible($v->public);            

            $editar =  (Permissions::has_perm($this->session['permissions'], $this->_permissions['editar']))? '&nbsp;<a id="editar"title="Editar"><i class="fa fa-edit"></i></a>&nbsp;' : '';

            $remover = (Permissions::has_perm($this->session['permissions'], $this->_permissions['remover']))? '&nbsp;<a id="remover" title="Excluir"><i class="fa fa-remove"></i></a>&nbsp;' : '';

            $arr[]  = $editar . $remover;
            $data[] = $arr;
        }

        $json_data = array( "draw"              =>  intval($request['draw']),
                            "recordsTotal"      => $rows->total,
                            "recordsFiltered"   => $rows->total,
                            "data"              => (!empty($data))? $data : []);

        echo json_encode($json_data);
    }
}