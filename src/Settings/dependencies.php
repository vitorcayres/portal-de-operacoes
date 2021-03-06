<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Twig View
$container['view'] = function ($container) {
    $settings = $container->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path']);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    $env = $view->getEnvironment();
    $env->addGlobal('messages', $container->get('flash')->getMessages());
    $env->addGlobal('session', $_SESSION);

    return $view;
};

// Register globally to app
$container['session'] = function ($container) {
    $session = new \Adbar\Session($container->get('settings')['session']['namespace']);
    return $session;
};

// Register provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};