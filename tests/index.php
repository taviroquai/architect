<?php

define('ARCH_PATH', realpath(__DIR__.'/../'));
require_once ARCH_PATH . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Arch\App;

\Arch\App::Instance('config/config.xml')->run();