<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;
use App\Helpers\Helpers_Interactivity_Filters;
use App\Helpers\Helpers_Interactivity_Channels;
use App\Helpers\Helpers_Interactivity_Offers;

class ChannelsController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Canais';
        $this->_subtitulo   = 'Canal';
        $this->_pagina      = 'canais';
        $this->_template    = '/interface/interatividade/canais';

        # Variaveis de ambiente
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'channels';

        # Permissões 
        $this->_permissoes = [
            'interface' => 'interatividade',            
            'listar'    => 'listar-canal',
            'inserir'   => 'inserir-canal',
            'editar'    => 'editar-canal',
            'remover'   => 'remover-canal'
        ];

        # Lista de Dias
        $this->_dias_da_semana = [
            'sunday'    => 'Domingo', 
            'monday'    => 'Segunda-Feira',
            'tuesday'   => 'Terça-Feira',
            'wednesday' => 'Quarta-Feira',
            'thursday'  => 'Quinta-Feira',
            'friday'    => 'Sexta-Feira', 
            'saturday'  => 'Sábado'
        ];

        # Filtros
        $this->_filterOffers    = Helpers_Interactivity_Filters::filterOffers();
        $this->_filterPartners  = Helpers_Interactivity_Filters::filterPartners();
        $this->_filterChannels  = Helpers_Interactivity_Filters::filterChannels();        
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
            'menu_'. $this->_pagina     => 'class=active',
            'ofertas'                   => $this->_filterOffers,
            'canais'                    => $this->_filterChannels
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $body['partner'] = ['id' => $body['partner']];
            $body['offer'] = ['id' => $body['offer']];            

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
            'ofertas'                   => $this->_filterOffers,
            'datas'                     => $this->_dias_da_semana                  
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);
        
        $offerData = Helpers_Interactivity_Offers::offerById($rows->offer->id);
        $partnerData = Helpers_Interactivity_Offers::partnerById($rows->partner->id);

        $rows->partner->name = $partnerData;
        $rows->offer->name = $offerData;

        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $body['partner'] = ['id' => $body['partner']];
            $body['offer'] = ['id' => $body['offer']];            

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
            'ofertas'                   => $this->_filterOffers,
            'datas'                     => $this->_dias_da_semana,
            'agendamento'               => (array) $rows->schedulingRules     
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