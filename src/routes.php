<?php


use Slim\Http\Request;
use Slim\Http\Response;

# Authentication
$app->get('/auth/login', App\Controllers\LoginController::class);
$app->post('/auth/login', App\Controllers\LoginController::class . ':login');

# Dashboard
$app->get('/dashboard', App\Controllers\DashboardController::class);

# Workplace
$app->get('/configuracoes/empresas/listar', App\Controllers\WorkplaceController::class .':listar');
$app->post('/configuracoes/empresas/inserir', App\Controllers\WorkplaceController::class .':inserir');
$app->get('/configuracoes/empresas/inserir', App\Controllers\WorkplaceController::class .':inserir')->setName('inserir-empresas');
$app->put('/configuracoes/empresas/editar', App\Controllers\WorkplaceController::class .':editar');
$app->delete('/configuracoes/empresas/remover', App\Controllers\WorkplaceController::class .':remover');

# AJAX: loadtable
$app->get('/ajax/loadtable', App\Libraries\Ajax::class . ':loadtable');