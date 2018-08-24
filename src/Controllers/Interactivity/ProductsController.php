<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\UserManagerPlatform;
use App\Libraries\Permissions;

class ProductsController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Produtos';
        $this->_subtitulo   = 'Produtos';
        $this->_pagina      = 'produtos';
        $this->_template    = '/interface/interatividade/produtos';

        # Variaveis de ambiente
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'products';

        # Permissões 
        $this->_permissoes = [
            'interface' => 'interatividade',            
            'listar'    => 'listar-produto',
            'detalhe'  => 'detalhe-produto'
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
            'token'                     => $this->_token,
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


    public function detalhe(Request $request, Response $response, $args)
    {
        // Recuperando os dados pelo id
        $id = $args['id'];

        // Recuperando os produtos
        $produto        = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);
        
        if (!isset($produto->status)) {
        
            // Recuperando o canal do produto
            $canal      = UserManagerPlatform::GET($this->_hostname, $this->_token, '/channels/' . $produto->channel->id);

            // Recuperando o parceiro do produto
            $parceiro   = UserManagerPlatform::GET($this->_hostname, $this->_token, '/partners/' . $produto->partner->id);

            // Recuperando as fraseologias do produto
            $fraseologias   = UserManagerPlatform::GET($this->_hostname, $this->_token, '/phraseologies?productId=' . $id);
            $arrFraseologias = [];

            foreach ($fraseologias->data as $v) {
                $arr = [];

                $arr['id']          = $v->id;
                $arr['carrier']     = $v->carrier;
                $arr['type']        = $v->type;
                $arr['message']     = $v->message;
                $arr['createdAt']   = date('d/m/Y H:i:s', strtotime($v->createdAt));
                $arr['updatedAt']   = date('d/m/Y H:i:s', strtotime($v->updatedAt));
                $arrFraseologias[] = $arr;
            }

            $carriers = '';
            foreach ($produto->carriers as $v){
                $carriers.= $v->carrier . ': '. $v->shortNumber . ', ';
            }

            // Destruindo variaveis inutilizadas
            unset($produto->uuid);
            unset($produto->partner);
            unset($produto->channel);      
            unset($produto->productsUuids);
            unset($produto->attributes);
            unset($produto->messageExtra);
            unset($produto->carriers);        

            // Inserindo valores no array do produto
            $produto->channels_name = $canal->name;
            $produto->partners_name = $parceiro->name;
            $produto->carriers      = $carriers;
            $produto->fraseologies  = $arrFraseologias;
        }

        return $this->container->view->render($response, $this->_template . '/detalhe.phtml', [
            'hostname'                  => $this->_hostname,
            'token'                     => $this->_token,
            'endpoint'                  => $this->_endpoint,
            'sessao'                    => $this->session,
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Detalhe do Produto',
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'id'                        => $args['id'],
            'produto'                   => $produto
        ]);        
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
            $arr[] = $v->type;                        

            $detalhes = (Permissions::has_perm($this->session['permissions'], $this->_permissoes['detalhe']))? '&nbsp;<a href="detalhe/'.$v->id.'" title="Detalhes do Produto"><i class="fa fa-plus-circle"></i></a>&nbsp;' : '';

            $arr[]  = $detalhes;
            $data[] = $arr;
        }

        $json_data = array( "draw"              =>  intval($request['draw']),
                            "recordsTotal"      => $rows->total,
                            "recordsFiltered"   => $rows->total,
                            "data"              => (!empty($data))? $data : []);

        echo json_encode($json_data);
    }
}