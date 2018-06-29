<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class LoginController{
	
	public function __construct($container){
		$this->container = $container;
	}

	public function auth(Request $request, Response $response, array $args){
		 return $this->container->renderer->render($response, '/interface/auth/login.phtml', $args);
	}
}