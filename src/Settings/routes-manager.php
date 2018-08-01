<?php
/**
* Gerenciador de Rotas da Plataforma
**/

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class)->setName('dashboard')->add(App\Middleware\Safety::class);

# Rota: Autenticação do Usuário
require __DIR__ . '../routes/login.php';

# Rota: Configurações
require __DIR__ . '../routes/configurations.php';

# Rota: Interatividade
require __DIR__ . '../routes/interactivity.php';