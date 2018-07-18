<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use \Adbar\Session;
use \Firebase\JWT\JWT;
use App\Libraries\UserManagerPlatform;

class LoginController
{
    private $_hostname = GDU_HOSTNAME;
    private $_token = GDU_TOKEN;

    public function __construct($container){
        $this->container = $container;
        $this->session = new \Adbar\Session;
    }    

    public function login(Request $request, Response $response, $args){

        if($request->isPost()){

            $params = $request->getParams();
            $rows = UserManagerPlatform::POST($this->_hostname, $this->_token, '/auth/login', $params);

            switch ($rows->status) {
                case 'success':

                    # Gerando token de autorização
                    $auth = JWT::decode($rows->token, SECRET_KEY, array('HS256'));        

                    # Criando sessão do usuário
                    $this->session->set(array(
                        'id'                    => $auth->data->id,
                        'enabled'               => $auth->data->enabled,
                        'force_pass_change'     => $auth->data->force_pass_change,
                        'password'              => $auth->data->password,
                        'token'                 => $rows->token,
                        'usergroup_id'          => $auth->data->usergroup_id,
                        'username'              => $auth->data->username,
                        'name'                  => $auth->data->name,
                        'superuser'             => $auth->data->superuser,
                        'workplace_id'          => $auth->data->workplace_id,
                        'permissions'           => $auth->data->permissions,
                        'last_change_password'  => $auth->data->last_change_password,
                        'create_date'           => $auth->data->create_date,
                        'updated_at'            => $auth->data->updated_at,
                        'expiration_at'         => $auth->data->expiration_at
                    ));

                    return $response->withJson($rows, 200)->withHeader('Content-type', 'application/json');
                    break;
                
                default:
                    return $response->withJson($rows, 401)
                    ->withHeader('Content-type', 'application/json');
                    break;
            }
        }

        return $this->container->view->render($response, '/interface/auth/login.phtml');
    }

    public function logout(Request $request, Response $response, $args){
        $id = $this->session["id"];
        $this->session->clear();
        return $response->withStatus(200)->withHeader('Location', '../auth/login');  
    }

    public function error(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, '/interface/error.phtml');        
    }

}