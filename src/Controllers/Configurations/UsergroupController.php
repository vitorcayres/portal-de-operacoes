<?php

namespace App\Controllers\Configurations;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;

class UsergroupController
{
    private $_hostname = GDU_HOSTNAME;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema = 'configuracoes';
        $this->_subtitulo  = 'Perfil';        
        $this->_titulo  = 'Configurações :: ' . $this->_subtitulo;
        $this->_pagina = 'perfil';
        $this->_endpoint = 'usergroup';
        $this->_template = '/interface/configuracoes/perfil';

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
            'hostname'          => $this->_hostname,
            'token'             => $this->_token
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        # Permissões
        $permissoes = UserManagerPlatform::GET($this->_hostname, $this->_token, '/permissions?limit=1000');

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
            'subtitulo'     => 'Nova '. $this->_subtitulo,                     
            'hostname'      => $this->_hostname,
            'token'         => $this->_token,
            'permissoes'    => $permissoes->data       
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {

        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);        

        # Grupo de Permissões
        $gp_permissoes = UserManagerPlatform::GET($this->_hostname, $this->_token, '/usergroup_has_permission?id='.$id.'&limit=1000');

        # Permissões
        $permissoes = UserManagerPlatform::GET($this->_hostname, $this->_token, '/permissions?limit=1000');

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
            'hostname'      => $this->_hostname,
            'token'         => $this->_token,
            'id'            => $args['id'],
            'rows'          => $rows->data,
            'permissoes'    => $permissoes->data,
            'gp_permissoes' => $gp_permissoes->data
        ]);        
    }

    public function remover(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];

        $rows = UserManagerPlatform::DELETE($this->_hostname, $this->_token, '/'. $this->_endpoint .'/', $id);

        switch ($rows->status) {
            case 'success':
                return true;
                break;
            
            default:
                return json_encode($rows);
                break;
        }       
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
            $arr[] = $v->description;            
            $arr[] = '<a href="editar/'.$v->id.'" title="Editar"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;&nbsp;<a id="delete" title="Excluir"><i class="fa fa-remove"></i></a>';
            $data[] = $arr;
        }

        $json_data = array( "draw"              =>  intval($request['draw']),
                            "recordsTotal"      => $rows->total,
                            "recordsFiltered"   => $rows->total,
                            "data"              => (!empty($data))? $data : []);

        echo json_encode($json_data);
    }
}