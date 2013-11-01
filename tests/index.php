<?php

// Autoload files using Composer autoload
require_once realpath( __DIR__ . '/../') . '/vendor/autoload.php';

// Create application
$config_path = realpath('config/config.xml');
$app = \Arch\App::Instance($config_path)->aliases();

// Add default route
$app->addRoute('/', function () use ($app) {
    $app->output->setContent('Hello Architect!');
});

// Run application
$app->run();