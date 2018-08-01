<?php

/**
* Rota de Interatividade
*/

# Grupo de rotas
$app->group('/interatividade', function () {

	# Parceiros
	$this->group('/parceiros', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Interactivity\PartnersController::class .':loadTable')->setName('listar-parceiro');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Interactivity\PartnersController::class .':listar')->setName('listar-parceiro');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Interactivity\PartnersController::class .':inserir')->setName('inserir-parceiro');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Interactivity\PartnersController::class .':editar')->setName('editar-parceiro');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Interactivity\PartnersController::class .':remover')->setName('remover-parceiro');
	});

	# Ofertas
	$this->group('/ofertas', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Interactivity\OffersController::class .':loadTable')->setName('listar-oferta');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Interactivity\OffersController::class .':listar')->setName('listar-oferta');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Interactivity\OffersController::class .':inserir')->setName('inserir-oferta');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Interactivity\OffersController::class .':editar')->setName('editar-oferta');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Interactivity\OffersController::class .':remover')->setName('remover-oferta');
	});

	# Canais
	$this->group('/canais', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Interactivity\ChannelsController::class .':loadTable')->setName('listar-canal');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Interactivity\ChannelsController::class .':listar')->setName('listar-canal');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Interactivity\ChannelsController::class .':inserir')->setName('inserir-canal');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Interactivity\ChannelsController::class .':editar')->setName('editar-canal');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Interactivity\ChannelsController::class .':remover')->setName('remover-canal');
	});

})->add(App\Middleware\Safety::class);