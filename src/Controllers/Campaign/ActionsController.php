<?php

namespace App\Controllers\Campaign;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;

class ActionsController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'campanhas';
        $this->_titulo      = 'Campanhas :: Ações';
        $this->_subtitulo   = 'Ações';
        $this->_pagina      = 'acoes';
        $this->_template    = '/interface/campanhas/acoes';

        # Variaveis de ambiente
        $this->_hostname    = '';
        $this->_token       = '';
        $this->_endpoint    = '';

        # Permissões 
        $this->_permissoes = [
            'listar'    => 'listar-acao',
            'inserir'   => 'inserir-acao',
            'editar'    => 'editar-acao',
            'remover'   => 'remover-acao'
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
            'permissoes'        => $this->_permissoes
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {

        if($request->isPost())
        {      

            $rows = UserManagerPlatform::POST($this->_hostname, $this->_token, '/'. $this->_endpoint, $body);

            switch ($rows->http_code) {
                case '201':
                    $$this->container->flash->addMessage('success', 'Registro inserido com sucesso!');
                    return $response->withStatus(200)->withHeader('Location', 'listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
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

            $rows = UserManagerPlatform::PUT($this->_hostname, $this->_token, '/'. $this->_endpoint .'/'. $id, $body);

            switch ($rows->http_code) {
                case '200':
                    $$this->container->flash->addMessage('success', 'Registro alterado com sucesso!');
                    return $response->withStatus(200)->withHeader('Location', '../listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
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
            'rows'          => $rows,
            'ofertas'       => $ofertas   
        ]);        
    }

    public function remover(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];

        $rows = UserManagerPlatform::DELETE($this->_hostname, $this->_token, '/'. $this->_endpoint .'/', $id);

        switch ($rows->http_code) {
            case '204':
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

        if($page)
            $parameters = "?page=". $page ."&limit=". $length;

        $more = [];

        $more = array(
            'name'            => (!empty($request['name']))? $request['name'] : '',
            'offerId'         => (!empty($request['offerId']))? $request['offerId'] : '',
            'messagesPerDay'  => (!empty($request['messagesPerDay']))? $request['messagesPerDay'] : ''                                    
        );

        if($more){
            if(!is_array($more)){ return false; }
            foreach ($more as $k => $v) {
                if(!empty($v)){
                    $parameters .= "&". $k ."=". $v;
                }
            }
        }

        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . $parameters);
        $data = [];

        foreach ($rows->data as $v) {
            $arr   = [];
            $arr[] = $v->id;
            $arr[] = $v->name;
            $arr[] = Helpers_Interactivity_Channels::offerById($v->offer->id);
            $arr[] = $v->messagesPerDay;
            $arr[] = Helpers_Interactivity_Channels::statusOffers($v->active);                                                               

            $editar =  (Permissions::has_perm($this->session['permissions'], $this->_permissoes['editar']))? '&nbsp;<a id="editar"title="Editar"><i class="fa fa-edit"></i></a>&nbsp;' : '';

            $remover = (Permissions::has_perm($this->session['permissions'], $this->_permissoes['remover']))? '&nbsp;<a id="remover" title="Excluir"><i class="fa fa-remove"></i></a>&nbsp;' : '';

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