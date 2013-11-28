<?php

namespace Arch;

/**
 * Description of Module
 *
 * @author mafonso
 */
class Module {
    
    public function __construct($name) {
        $loader = $name.DIRECTORY_SEPARATOR.'src'.
                DIRECTORY_SEPARATOR.'autoload.php';
        if (file_exists($loader)) {
            require_once $loader;
        }
        $config = $name.DIRECTORY_SEPARATOR.'config.php';
        if (file_exists($config)) {
            require_once $config;
        }
    }
}
