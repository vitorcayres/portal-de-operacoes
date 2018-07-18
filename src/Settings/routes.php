<?php

# Authentication
$app->group('/auth', function () {
	# Login | Logout
	$this->map(['GET', 'POST'], '/login',	App\Controllers\LoginController::class . ':login');
	$this->map(['GET', 'POST'], '/logout',	App\Controllers\LoginController::class . ':logout');
	$this->map(['GET'], 		'/error',	App\Controllers\LoginController::class . ':error');
});

# Dashboard
$app->get('/dashboard', 
	App\Controllers\DashboardController::class)
	->setName('dashboard')
	->add(App\Middleware\Safety::class);

# Configurações da Interface
$app->group('/configuracoes', function () {

	# Empresas
	$this->group('/empresas', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\WorkplaceController::class .':loadtable');		
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\WorkplaceController::class .':listar')->setName('listar-empresa');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\WorkplaceController::class .':inserir')->setName('inserir-empresa');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\WorkplaceController::class .':editar')->setName('editar-empresa');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\WorkplaceController::class .':remover')->setName('remover-empresa');
	});

	# Usuários
	$this->group('/usuarios', function () {
		$this->map(['GET'], 			'/loadtable',		App\Controllers\Configurations\UsersController::class .':loadtable');		
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\UsersController::class .':listar')->setName('listar-usuario');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\UsersController::class .':inserir')->setName('inserir-usuario');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\UsersController::class .':editar')->setName('editar-usuario');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\UsersController::class .':remover')->setName('remover-usuario');
	});	

	# Perfil
	$this->group('/perfil', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\UsergroupController::class .':loadtable');		
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\UsergroupController::class .':listar')->setName('listar-perfil');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\UsergroupController::class .':inserir')->setName('inserir-perfil');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\UsergroupController::class .':editar')->setName('editar-perfil');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\UsergroupController::class .':remover')->setName('remover-perfil');
	});

	# Permissão
	$this->group('/permissoes', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Configurations\PermissionsController::class .':loadtable');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Configurations\PermissionsController::class .':listar')->setName('listar-permissao');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Configurations\PermissionsController::class .':inserir')->setName('inserir-permissao');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Configurations\PermissionsController::class .':editar')->setName('editar-permissao');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Configurations\PermissionsController::class .':remover')->setName('remover-permissao');
	});	
})->add(App\Middleware\Safety::class);