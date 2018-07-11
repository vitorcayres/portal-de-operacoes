<?php

# Authentication
$app->group('/auth', function () {
	# Login | Logout
	$this->map(['GET', 'POST'], '/login', App\Controllers\LoginController::class . ':login');
	$this->map(['GET', 'POST'], '/logout', App\Controllers\LoginController::class . ':logout');
});

# Configurações da Interface
$app->group('/configuracoes', function () {

	# Empresas
	$this->group('/empresas', function () {
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\WorkplaceController::class .':listar');
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\WorkplaceController::class .':loadtable');		
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\WorkplaceController::class .':inserir')->setName('inserir-empresas');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\WorkplaceController::class .':editar')->setName('editar-empresas');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\WorkplaceController::class .':remover');
	});

	# Usuários
	$this->group('/usuarios', function () {
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\UsersController::class .':listar');
		$this->map(['GET'], 			'/loadtable',		App\Controllers\Configurations\UsersController::class .':loadtable');		
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\UsersController::class .':inserir')->setName('inserir-usuarios');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\UsersController::class .':editar')->setName('editar-usuarios');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\UsersController::class .':remover');
	});	

	# Perfil
	$this->group('/perfil', function () {
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\UsergroupController::class .':listar');
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\UsergroupController::class .':loadtable');		
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\UsergroupController::class .':inserir')->setName('inserir-perfil');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\UsergroupController::class .':editar')->setName('editar-perfil');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\UsergroupController::class .':remover');
	});

	# Permissão
	$this->group('/permissoes', function () {
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\PermissionsController::class .':listar');
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\PermissionsController::class .':loadtable');		
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\PermissionsController::class .':inserir')->setName('inserir-permissao');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\PermissionsController::class .':editar')->setName('editar-permissao');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\PermissionsController::class .':remover');
	});	
})->add(App\Middleware\Authentication::class);

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class)
->add(App\Middleware\Authentication::class);