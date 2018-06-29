<?php

// Routes
$app->get('/auth/login', App\Controllers\LoginController::class . ':auth');