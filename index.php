<?php

require_once 'config.php';
require_once 'autoload.php';

// get application
$app = App::Instance();

// runs the application
$app->run();