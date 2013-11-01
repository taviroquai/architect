<?php

// Autoload files using Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../aliases.php';

// Create application
$config_path = realpath('config/config.xml');
$app = \Arch\App::Instance($config_path);

// Add default route
$app->addRoute('/', function () use ($app) {
    $app->output->setContent('Hello Architect!');
});

// Run application
$app->run();