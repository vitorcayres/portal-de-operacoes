<?php

# AJAX: loadtable
$app->get('/ajax/loadtable', App\Libraries\Ajax::class . ':loadtable');

# Authentication

$app->group('/auth', function () {

	# Login | Logout
	$this->map(['GET', 'POST'], '/login', App\Controllers\LoginController::class . ':login');
	$this->map(['GET', 'POST'], '/logout', App\Controllers\LoginController::class . ':logout');
});

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class);

# Configurações da Interface
$app->group('/configuracoes', function () {

	# Empresas
	$this->group('/empresas', function () {
		$this->map(['GET'], '/listar', App\Controllers\WorkplaceController::class .':listar');
	    $this->map(['GET', 'POST'], '/inserir', App\Controllers\WorkplaceController::class .':inserir')->setName('inserir-empresas');
	    $this->map(['GET', 'POST'], '/editar/{id}', App\Controllers\WorkplaceController::class .':editar')->setName('editar-empresas');
	    $this->map(['POST', 'DELETE'], '/remover/{id}', App\Controllers\WorkplaceController::class .':remover');
	});
	
});