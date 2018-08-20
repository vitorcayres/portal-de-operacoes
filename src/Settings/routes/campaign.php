<?php

/**
* Rota de Campanhas
*/

# Grupo de rotas
$app->group('/campanhas', function () {

	# consumers
	$this->group('/consumers', function () {
		$this->map(['GET'], 			'/loadtable', 		App\Controllers\Campaign\ConsumersController::class .':loadTable')->setName('listar-consumer');
		$this->map(['GET'], 			'/listar', 			App\Controllers\Campaign\ConsumersController::class .':listar')->setName('listar-consumer');
	    $this->map(['GET', 'POST'], 	'/inserir', 		App\Controllers\Campaign\ConsumersController::class .':inserir')->setName('inserir-consumer');
	    $this->map(['GET', 'POST'], 	'/editar/{id}', 	App\Controllers\Campaign\ConsumersController::class .':editar')->setName('editar-consumer');
	    $this->map(['POST', 'DELETE'], 	'/remover/{id}', 	App\Controllers\Campaign\ConsumersController::class .':remover')->setName('remover-consumer');
	});
	
})->add(App\Middleware\Safety::class);