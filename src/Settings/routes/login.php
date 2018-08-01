<?php

/**
* Rota das Configurações
*/

# Login
$app->get('/', function (){	header('Location: auth/login '); exit(); });

# Grupo de rotas
$app->group('/auth', function () {
	# Login | Logout
	$this->map(['GET', 'POST'], '/login',	App\Controllers\LoginController::class . ':login');
	$this->map(['GET', 'POST'], '/logout',	App\Controllers\LoginController::class . ':logout');
	$this->map(['GET'], 		'/error',	App\Controllers\LoginController::class . ':error');
});