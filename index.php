<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'development'));

require __DIR__ . '../vendor/autoload.php';

// Set up configurations
require __DIR__ . '../src/Settings/constants.php';

// Instantiate the app
$settings = require __DIR__ . '../src/Settings/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '../src/Settings/dependencies.php';

// Register middleware
require __DIR__ . '../src/Settings/middleware.php';

// Register routes
require __DIR__ . '../src/Settings/routes-manager.php';

// Run app
$app->run();