<?php

// Autoload files using Composer autoload
require_once realpath(__DIR__ . '/../vendor/autoload.php');
require_once realpath(__DIR__ . '/../aliases.php');

// Create application
$arch = new \Arch\App('config.xml');

// Add default route
r('/', function () {
    o('Hello Architect!');
});

// Run application
$arch->run();