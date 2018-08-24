<?php

namespace App\Controllers\Interactivity;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use App\Libraries\Permissions;
use App\Libraries\UserManagerPlatform;
use App\Helpers\Helpers_Interactivity_News;
use App\Helpers\Helpers_Interactivity_Offers;

class NewsController
{
    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
        
        # Parametros de Texto
        $this->_sistema     = 'interatividade';
        $this->_titulo      = 'Interatividade :: Notícias';
        $this->_subtitulo   = 'Notícia';
        $this->_pagina      = 'noticias';
        $this->_template    = '/interface/interatividade/noticias';

        # Variaveis de ambiente
        $this->_hostname    = INTERACTIVITY_HOSTNAME;
        $this->_token       = INTERACTIVITY_TOKEN;
        $this->_endpoint    = 'news';

        # Permissões 
        $this->_permissoes = [
            'interface' => 'interatividade',            
            'listar'    => 'listar-noticia',
            'inserir'   => 'inserir-noticia',
            'editar'    => 'editar-noticia',
            'remover'   => 'remover-noticia',
            'importar'  => 'importar-noticia'
        ];         
    }    

    public function listar(Request $request, Response $response, $args)
    {
        // Todas as ofertas
        $ofertas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/offers?limit=1000');
        $ofertas = Helpers_Interactivity_Offers::getNameAndIdOffers($ofertas);

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
            'ofertas'                   => $ofertas            
        ]);
    }

    public function inserir(Request $request, Response $response, $args)
    {
        // Todas as ofertas
        $ofertas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/offers?limit=1000');
        $ofertas = Helpers_Interactivity_Offers::getNameAndIdOffers($ofertas);

        if($request->isPost())
        {
            $body = $request->getParsedBody();

            $body['offer']       = ['id' => $body['offer']];
            $body['channel']     = ['id' => $body['channel']];
            $body['scheduledAt'] = date("Y-m-d", strtotime($body['date'])).'T'.$body['hour'].':00O';

            unset($body['shortUrl']);
            unset($body['date']);
            unset($body['hour']);                                    

            $rows = UserManagerPlatform::POST($this->_hostname, $this->_token, '/'. $this->_endpoint, $body);

            switch ($rows->http_code) {
                case '201':
                    $this->container->flash->addMessage('success', 'Registro criado com sucesso!');
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
            'subtitulo'                 => 'Nova ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'ofertas'                   => $ofertas      
        ]);
    }

    public function editar(Request $request, Response $response, $args)
    {
        // Todas as ofertas
        $ofertas = UserManagerPlatform::GET($this->_hostname, $this->_token, '/offers?limit=1000');
        $ofertas = Helpers_Interactivity_Offers::getNameAndIdOffers($ofertas);        

        // Recupera os dados pelo id
        $id = $args['id'];
        $rows = UserManagerPlatform::GET($this->_hostname, $this->_token, '/'. $this->_endpoint . '/' . $id);

        // Recupera nome do canal
        $channel_name = Helpers_Interactivity_News::channelById($rows->channel->id);
        $rows->channel->name = $channel_name;

        // Formata a data e hora da noticia
        $rows->date = date("d/m/Y", strtotime(substr($rows->scheduledAt, 0, 10)));
        $rows->hour = substr($rows->scheduledAt, 11, 5);        

        // Destroi parametros inutilizaveis
        unset($rows->id);
        unset($rows->createdAt);
        unset($rows->updatedAt);
        unset($rows->scheduledAt);
        unset($rows->http_code);
        unset($rows->status);

        if($request->isPost())
        {
            $body = $request->getParsedBody();

            $body['offer']       = ['id' => $body['offer']];
            $body['channel']     = ['id' => $body['channel']];
            $body['scheduledAt'] = date("Y-m-d", strtotime($body['date'])).'T'.$body['hour'].':00O';

            // Destroi parametros inutilizaveis
            unset($body['shortUrl']);
            unset($body['date']);
            unset($body['hour']);                                    

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
            'permissoes'                => $this->_permissoes,
            'pagina'                    => $this->_pagina,
            'titulo'                    => $this->_titulo,
            'subtitulo'                 => 'Editar ' . $this->_subtitulo,
            'menu_sistema'              => $this->_sistema,
            'menu_'.$this->_pagina      => 'class=active',
            'id'                        => $args['id'],
            'rows'                      => $rows,
            'ofertas'                   => $ofertas   
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

    public function importar(Request $request, Response $response, $args){

        if($request->isPost())
        {
            $body = $request->getParsedBody();
            $channelId = $body['channel'];

            $tmp_filename = basename($_FILES["file"]["name"]);
            $tmp_extensao = substr($tmp_filename, -3);
            $new_filename = substr($tmp_filename, 0, -4) .'-'. microtime(true) .'.'. $tmp_extensao;

            $target_file = INTERACTIVITY_APPLICATION_PATH . $new_filename;
            $filedata = $_FILES['file']['tmp_name'];
            $filesize = $_FILES['file']['size'];

            $uploadOk = 1;
            $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if (file_exists($target_file)) {
                $this->container->flash->addMessage('error', 'O arquivo já existe. Tente novamente!');
                $uploadOk = 0;
            }

            if ($filesize > 31457280) {
                $this->container->flash->addMessage('error', 'Seu arquivo é muito grande, superior a 30MB. Tente novamente!');
                $uploadOk = 0;
            }     

            if ($uploadOk == 1) {
                if ($filedata != '') {
                    if (move_uploaded_file($filedata, $target_file)) {

                        $rows = UserManagerPlatform::UPLOAD($this->_hostname, $this->_token, '/news/channels/'. $channelId . '/', $target_file);

                        switch ($rows->http_code) {
                            case '201':
                                $this->container->flash->addMessage('success', 'Arquivo importado com sucesso!');
                                return $response->withStatus(200)->withHeader('Location', 'listar');
                                break;
                            
                            default:
                                $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
                                return $response->withHeader('Location', 'inserir');                
                                break;
                        }
                    } else {
                        $this->container->flash->addMessage('error', 'Ops, ocorreu um erro. Tente novamente!');
                    }
                }
            }
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
            'offerId'    => (!empty($request['offerId']))? $request['offerId'] : '',
            'channelId'  => (!empty($request['channelId']))? $request['channelId'] : '',
            'status'     => (!empty($request['status']))? $request['status'] : ''                        
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
            $arr[] = Helpers_Interactivity_News::offerById($v->offer->id);
            $arr[] = Helpers_Interactivity_News::channelById($v->channel->id);
            $arr[] = '<textarea class="form-control" style="width: 100%; height: 70px; color: #31708f; background-color: #d9edf7; border-color: #bce8f1;" disabled>'. $v->message .'</textarea>';
            $arr[] = Helpers_Interactivity_News::statusNews($v->status);
            $arr[] = date('d/m/Y H:i:s', strtotime($v->scheduledAt));
            $arr[] = date('d/m/Y H:i:s', strtotime($v->createdAt));
            $arr[] = date('d/m/Y H:i:s', strtotime($v->updatedAt));

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