<?php

// Default PHP configuration
error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', true);
define('DS', DIRECTORY_SEPARATOR);
define('BASEPATH', __DIR__);

// Set configuration file path
$filename = BASEPATH.'/config/development.xml';

// Require class autoloader
require_once 'autoload.php';

// Get application
$app = App::Instance($filename);

// Run application
$app->run();