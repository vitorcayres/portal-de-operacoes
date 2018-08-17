<?php
/**
* Gerenciador de Rotas da Plataforma
**/

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class)->setName('dashboard')->add(App\Middleware\Safety::class);

# Ajax
$app->group('/ajax', function () {
	$this->map(['POST'], '/busca-canal',	App\Controllers\AjaxController::class .':getChannelsById');
	$this->map(['POST'], '/busca-parceiro',	App\Controllers\AjaxController::class .':getPartnersById');
	$this->map(['POST'], '/encurta-url',	App\Controllers\AjaxController::class .':shortUrl');		
});

# Rota: Autenticação do Usuário
require __DIR__ . '../routes/login.php';

# Rota: Configurações
require __DIR__ . '../routes/configurations.php';

# Rota: Interatividade
require __DIR__ . '../routes/interactivity.php';