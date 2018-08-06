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

	# Notícias
	$this->group('/noticias', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Interactivity\NewsController::class .':loadTable')->setName('listar-noticia');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Interactivity\NewsController::class .':listar')->setName('listar-noticia');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Interactivity\NewsController::class .':inserir')->setName('inserir-noticia');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Interactivity\NewsController::class .':editar')->setName('editar-noticia');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Interactivity\NewsController::class .':remover')->setName('remover-noticia');
	});	

	# Produtos
	$this->group('/produtos', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Interactivity\ProductsController::class .':loadTable')->setName('listar-produto');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Interactivity\ProductsController::class .':listar')->setName('listar-produto');
	    $this->map(['GET'],			 	'/detalhe/{id}', 	App\Controllers\Interactivity\ProductsController::class .':detalhe')->setName('detalhe-produto');
	});

	# Luckynumber
	$this->group('/luckynumber', function () {
		$this->map(['GET'], 			'/loadtable', 	App\Controllers\Interactivity\LuckynumberController::class.':loadTable')->setName('listar-luckynumber');
		$this->map(['GET'], 			'/listar', 		App\Controllers\Interactivity\LuckynumberController::class .':listar')->setName('listar-luckynumber');
	    $this->map(['GET', 'POST'], 	'/inserir', 	App\Controllers\Interactivity\LuckynumberController::class .':inserir')->setName('inserir-luckynumber');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', App\Controllers\Interactivity\LuckynumberController::class .':editar')->setName('editar-luckynumber');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}',App\Controllers\Interactivity\LuckynumberController::class .':remover')->setName('remover-luckynumber');
	});	

	# Fraseologias
	$this->group('/fraseologias', function () {
		$this->map(['GET'], 		'/loadtable', 	App\Controllers\Interactivity\PhraseologiesController::class.':loadTable')->setName('listar-fraseologia');
		$this->map(['GET'], 		'/listar', 		App\Controllers\Interactivity\PhraseologiesController::class .':listar')->setName('listar-fraseologia');
	    $this->map(['GET', 'POST'], '/inserir', 	App\Controllers\Interactivity\PhraseologiesController::class .':inserir')->setName('inserir-fraseologia');
	    $this->map(['GET', 'POST'], '/editar/{id}', App\Controllers\Interactivity\PhraseologiesController::class .':editar')->setName('editar-fraseologia');
	    $this->map(['POST', 'DELETE'],'/remover/{id}',App\Controllers\Interactivity\PhraseologiesController	::class .':remover')->setName('remover-fraseologia');
	});	


})->add(App\Middleware\Safety::class);