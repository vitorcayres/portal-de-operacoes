<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;
use App\Helpers\Helpers_Interactivity_Offers;

class OffersController
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
            'interface' => 'interatividade',
            'listar'    => 'listar-oferta',
            'inserir'   => 'inserir-oferta',
            'editar'    => 'editar-oferta',
            'remover'   => 'remover-oferta'
        ];         
    }    

    public function listar(Request $request, Response $response, $args)
    {

        // Todas as ofertas
        $ofertas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/offers?limit=1000');
        $ofertas = Helpers_Interactivity_Offers::getNameAndIdOffers($ofertas);

        // Todos os parceiros
        $parceiros = UserManagerPlatform::GET($this->_hostname, $this->_token, '/partners?limit=1000');
        $parceiros = Helpers_Interactivity_Offers::getNameAndIdPartners($parceiros);      

        return $this->container->view->render($response, $this->_template . '/listar.phtml', [
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissions,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Listar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'parceiros'                 => $parceiros,
            'ofertas'                   => $ofertas        
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        // Todos os parceiros
        $parceiros = UserManagerPlatform::GET($this->_hostname, $this->_token, '/partners?limit=1000');
        $parceiros = Helpers_Interactivity_Offers::getNameAndIdPartners($parceiros);           

        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $body['partner'] = ['id' => $body['partner']];

            $rows = UserManagerPlatform::POST($this->_hostname, $this->_token, '/'. $this->_endpoint, $body);

            switch ($rows->http_code) {
                case '201':
                    $this->container->flash->addMessage('success', 'Registro inserido com sucesso!');
                    return $response->withStatus(200)->withHeader('Location', 'listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
                    return $response->withHeader('Location', 'inserir');                
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/inserir.phtml', [
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissions,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Nova ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'parceiros'                 => $parceiros               
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Todos os parceiros
        $parceiros = UserManagerPlatform::GET($this->_hostname, $this->_token, '/partners?limit=1000');
        $parceiros = Helpers_Interactivity_Offers::getNameAndIdPartners($parceiros);    

        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);

        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $body['partner'] = ['id' => $body['partner']];

            $rows = UserManagerPlatform::PUT($this->_hostname, $this->_token, '/'. $this->_endpoint .'/'. $id, $body);

            switch ($rows->http_code) {
                case '200':
                    $this->container->flash->addMessage('success', 'Registro alterado com sucesso!');
                    return $response->withStatus(200)->withHeader('Location', '../listar');
                    break;
                
                default:
                    $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
                    return $response->withHeader('Location', '../editar/'. $id);
                    break;
            }
        }

        return $this->container->view->render($response, $this->_template . '/editar.phtml', [
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissions,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Editar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'id'                        => $args['id'],
            'rows'                      => $rows,
            'parceiros'                 => $parceiros             
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
            'name'          => (!empty($request['name']))? str_replace(" ", "-", $request['name']) : '',
            'description'   => (!empty($request['description']))? $request['description'] : '',
            'partnerId'     => (!empty($request['partnerId']))? $request['partnerId'] : '',
            'public'        => (!empty($request['public']))? is_string($request['public']) : ''                                    
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