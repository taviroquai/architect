<?php

// IMPORTANT!!! Define base path constant
define('BASE_PATH', __DIR__);

// IMPORTANT!!! Require class autoloader
require_once 'src/autoload.php';
require_once 'src/aliases.php';

// IMPORTANT!!! Give configuration file and run application
$config = BASE_PATH."/config/development.xml";
\Arch\App::Instance($config)->run();
