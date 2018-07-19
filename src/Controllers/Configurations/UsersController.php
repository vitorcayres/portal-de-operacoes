<?php

namespace App\Controllers\Configurations;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;

class UsersController
{
    private $_hostname = GDU_HOSTNAME;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'configuracoes';
        $this->_subtitulo   = 'Usuário';        
        $this->_titulo      = 'Configurações :: ' . $this->_subtitulo;
        $this->_pagina      = 'usuarios';
        $this->_endpoint    = 'users';
        $this->_template    = '/interface/configuracoes/usuarios';

        # Token do usuário
        $this->_token = $this->session->get('token');
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
            'token'             => $this->_token
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        # GET: Empresas
        $empresas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/workplace');

        # GET: Perfil
        $perfil = UserManagerPlatform::GET($this->_hostname, $this->_token, '/usergroup');

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
                    foreach ($rows->message as $key => $message) {
                        if(array_key_exists(0, $message)){
                            $this->container->flash->addMessage('error', $message[0]);
                        }else{
                            $this->container->flash->addMessage('error', $message);
                        }
                    }
                    return $response->withHeader('Location', 'inserir');                
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/inserir.phtml', [
            'endpoint'      => $this->_endpoint,
            'pagina'        => $this->_pagina,
            'menu_sistema'  => $this->_sistema,
            'titulo'        => $this->_titulo,
            'subtitulo'     => 'Nova '. $this->_subtitulo,
            'sessao'        => $this->session,                                 
            'hostname'      => $this->_hostname,
            'token'         => $this->_token,
            'empresas'      => $empresas->data,
            'perfil'        => $perfil->data     
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);

        # GET: Empresas
        $empresas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/workplace');

        # GET: Perfil
        $perfil = UserManagerPlatform::GET($this->_hostname, $this->_token, '/usergroup');

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
                    if(count((array)$rows->message) > 1){
                        foreach ($rows->message as $message) {
                            $this->container->flash->addMessage('error', $message[0]);                    
                        }
                    }
                    else{
                        $this->container->flash->addMessage('error', $rows->message);
                    }
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
            'rows'          => $rows->data,
            'empresas'      => $empresas->data,
            'perfil'        => $perfil->data    
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


public function alterar_senha(Request $request, Response $response, $args){

        if($request->isPost())
        {
            // Recuperando os dados pelo id
            $id = $args['id'];

            // Parametros da requisição
            $params = (object) $request->getParams();

            $rows = UserManagerPlatform::PUT($this->_hostname, $this->_token, '/change_password/', $id);

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

        return $this->container->view->render($response, $this->_template . '/alterar-senha.phtml', [
            'endpoint'          => $this->_endpoint,
            'pagina'            => $this->_pagina,
            'menu_sistema'      => $this->_sistema,
            'titulo'            => $this->_titulo,
            'subtitulo'         => 'Alterar Senha do ' . $this->_subtitulo,
            'sessao'            => $this->session,            
            'hostname'          => $this->_hostname,
            'token'             => $this->_token,
            'id'                => $args['id'],            
        ]);

}


    public function loadtable(Request $request, Response $response, $args)
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
            $arr[] = $v->username;
            $arr[] = ($v->enabled == '-1')? '<span class="badge badge-danger">Inativo</span>' : '<span class="badge badge-primary">Ativo</span>';
            $arr[] = '<a href="alterar-senha/'.$v->id.'" title="Alterar Senha"><i class="fa fa-asterisk"></i></a>&nbsp;|&nbsp;&nbsp;<a href="editar/'.$v->id.'" title="Editar"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;&nbsp;<a id="delete" title="Excluir"><i class="fa fa-remove"></i></a>';
            $data[] = $arr;
        }

        $json_data = array( "draw"              =>  intval($request['draw']),
                            "recordsTotal"      => $rows->total,
                            "recordsFiltered"   => $rows->total,
                            "data"              => (!empty($data))? $data : []);

        echo json_encode($json_data);
    }
}