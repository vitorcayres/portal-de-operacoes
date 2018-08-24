<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;
use App\Helpers\Helpers_Interactivity_Phraseologies;
use App\Helpers\Helpers_Interactivity_Luckynumber;



class PhraseologiesController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Fraseologias';
        $this->_subtitulo   = 'Fraseologias';
        $this->_pagina      = 'fraseologias';
        $this->_template    = '/interface/interatividade/fraseologias';

        # Variaveis de ambiente da API de Interatividade 
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'phraseologies';
        $this->_la          = INTERACTIVITY_LA;
        $this->_operadora   = INTERACTIVITY_CARRIER;        

        # Variaveis de ambiente da API de Campanhas
        $this->_hostname_campaign    = CAMPAIGN_HOSTNAME;
        $this->_token_campaign       = CAMPAIGN_TOKEN;        
        $this->_endpoint_campaign    = 'getcampaigns';

        # Variaveis de ambiente da API de FlushPlatformUnifield
        $this->_hostname_flush_pu    = PLATFORM_UNIFIELD_FLUSHALL_CACHE;

        # Permissões 
        $this->_permissoes = [
            'interface' => 'interatividade',            
            'listar'    => 'listar-fraseologia',
            'inserir'   => 'inserir-fraseologia',
            'editar'    => 'editar-fraseologia',
            'remover'   => 'remover-fraseologia',
            'publicar'  => 'publicar-fraseologia'
        ];         
    }    

    public function listar(Request $request, Response $response, $args)
    {
        // Todas as campanhas
        $campanhas = UserManagerPlatform::GET($this->_hostname_campaign, $this->_token_campaign, '/'. $this->_endpoint_campaign);
        $campanhas = Helpers_Interactivity_Phraseologies::getNameAndIdCampaign($campanhas);

        // Todos os produtos
        $produtos = UserManagerPlatform::GET($this->_hostname, $this->_token, '/products?limit=10000');
        $produtos = Helpers_Interactivity_Phraseologies::getNameAndIdProducts($produtos);

        // Tipos de fraseologias
        $tipos = UserManagerPlatform::GET($this->_hostname, $this->_token, '/phraseologies/all-types');
        $tipos = Helpers_Interactivity_Phraseologies::getNameAndIdTypes($tipos);        

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
            'campanhas'                 => $campanhas,
            'produtos'                  => $produtos,
            'la'                        => $this->_la,
            'tipos'                     => $tipos,
            'operadoras'                => $this->_operadora
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
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Nova ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active'    
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
            'rows'                      => $rows      
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

    public function publicar(Request $request, Response $response, $args)
    {
        $rows = UserManagerPlatform::GET($this->_hostname_flush_pu, '', '');

        switch ($rows->status) {
            case 'OK':
                return $response->withJson($rows, 200)
                ->withHeader('Content-type', 'application/json');  
                break;
            
            default:
                return $response->withJson(['status' => false, 'message' => 'Ocorreu um erro ao publicar as fraseologias!'], 400)
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
        unset($request['fraseologias_length']);

        if($page)
            $parameters = "?page=". $page ."&limit=". $length;

        $more = [];

        $more = array(
            'productId'     => (!empty($request['productId']))?     $request['productId'] : '',
            'campaignUuid'  => (!empty($request['campaignUuid']))?  $request['campaignUuid'] : '',
            'type'          => (!empty($request['type']))?          $request['type'] : '',
            'carrier'       => (!empty($request['carrier']))?       $request['carrier'] : '',
            'shortNumber'   => (!empty($request['shortNumber']))?   $request['shortNumber'] : ''
        );

        if($more){
            if(!is_array($more)){ return false; }
            foreach ($more as $k => $v) {
                if(!empty($v)){
                    $parameters .= "&". $k ."=". $v;
                }
            }
        }

        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint .'/all-grouped'. $parameters);
        $data = [];

        foreach ($rows->data as $v) {
            $arr   = [];
            $arr[] = $v->id;
            $arr[] = $v->campaignName;
            $arr[] = (!empty($v->product->id))? '' : '';            
            $arr[] = $v->shortNumber;
            $arr[] = $v->type->briefDescription;
            $arr[] = Helpers_Interactivity_Phraseologies::badgeColorForCarrier($v->carrier);

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