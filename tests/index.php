<?php

// Autoload files using Composer autoload
define('ARCH_PATH', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once realpath(ARCH_PATH) . DIRECTORY_SEPARATOR.
        'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

// Create application
$config_path = 'config'.DIRECTORY_SEPARATOR.'config.xml';
$app = \Arch\App::Instance($config_path);

// Add default route
$app->addRoute('/', function () use ($app) {
    $app->output->setContent('Hello Architect!');
});

// Run application
$app->run();