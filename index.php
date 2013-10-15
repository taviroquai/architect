<?php

// IMPORTANT!!! Define base path constant
define('BASE_PATH', __DIR__);

// IMPORTANT!!! Require class autoloader
require_once 'src/autoload.php';
require_once 'src/aliases.php';

// IMPORTANT!!! Run application
$env = 'development';
\Arch\App::Instance($env)->run();
