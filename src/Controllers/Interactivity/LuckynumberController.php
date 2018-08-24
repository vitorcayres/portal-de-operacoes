<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;
use App\Helpers\Helpers_Interactivity_Luckynumber;

class LuckynumberController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Números da Sorte';
        $this->_subtitulo   = 'Números da Sorte';
        $this->_pagina      = 'luckynumber';
        $this->_template    = '/interface/interatividade/luckynumber';

        # Variaveis de ambiente
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'lucky-number-configurations';

        # Permissões 
        $this->_permissoes = [
            'interface' => 'interatividade',            
            'listar'    => 'listar-luckynumber',
            'inserir'   => 'inserir-luckynumber',
            'editar'    => 'editar-luckynumber',
            'remover'   => 'remover-luckynumber'
        ];         
    }    

    public function listar(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, $this->_template . '/listar.phtml', [
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Listar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',            
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        // Todos os tipos
        $tipos = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/raffle-types?limit=1000');
        $tipos = Helpers_Interactivity_Luckynumber::getNameAndIdTypes($tipos);

        // Todos os cenarios
        $cenarios = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/types?limit=1000');
        $cenarios = Helpers_Interactivity_Luckynumber::getNameAndIdTypes($cenarios);        

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
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Novo ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',  
            'tipos'                     => $tipos,
            'cenarios'                  => $cenarios     
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Todos os tipos
        $tipos = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/raffle-types?limit=1000');
        $tipos = Helpers_Interactivity_Luckynumber::getNameAndIdTypes($tipos);

        // Todos os cenarios
        $cenarios = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/types?limit=1000');
        $cenarios = Helpers_Interactivity_Luckynumber::getNameAndIdTypes($cenarios);       

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
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Editar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active', 
            'id'                        => $args['id'],
            'rows'                      => $rows,
            'tipos'                     => $tipos,
            'cenarios'                  => $cenarios                
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
            $arr[] = $v->type;
            $arr[] = $v->scenario;
            $arr[] = $v->quantity;            

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