<?php

/**
**  Rotas
**/

$app->map(['GET', 'POST', 'PUT', 'DELETE'], '[/{params:.*}]', App\Controllers\AdminController::class . ':execute');