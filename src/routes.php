<?php

# Authentication
$app->get('/auth/login', App\Controllers\LoginController::class);
$app->post('/auth/login', App\Controllers\LoginController::class . ':login');

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class);

# Workplace
$app->get('/configurations/workplace/list', App\Controllers\WorkplaceController::class .':list');
$app->get('/configurations/workplace/add', App\Controllers\WorkplaceController::class .':add');
$app->post('/configurations/workplace/add', App\Controllers\WorkplaceController::class .':add');
$app->put('/configurations/workplace/modify', App\Controllers\WorkplaceController::class .':modify');
$app->delete('/configurations/workplace/remove', App\Controllers\WorkplaceController::class .':remove');

# AJAX: loadtable
$app->get('/ajax/loadtable', App\Libraries\Ajax::class . ':loadtable');