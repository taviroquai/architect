<?php

// Autoload files using Composer autoload
require_once realpath(__DIR__ . '/../vendor/autoload.php');
require_once realpath(__DIR__ . '/../aliases.php');

// Create application
$app = \Arch\App::Instance('config.xml');

// Add default route
r('/', function () {
    o('Hello Architect!');
});

// Run application
$app->run();