<?php

namespace Arch;

/**
 * Description of Module
 *
 * @author mafonso
 */
class Module {
    
    /**
     * Holds the module directory path
     * @var string
     */
    protected $path;
    
    /**
     * Holds the class loader path
     * @var string
     */
    protected $class_loader_path;
    
    /**
     * Holds the configuration file path
     * @var string
     */
    protected $config_file_path;
    
    /**
     * Returns a new module
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->class_loader_path = $this->path.DIRECTORY_SEPARATOR.'src'.
                DIRECTORY_SEPARATOR.'autoload.php';
        $this->config_file_path = $this->path.DIRECTORY_SEPARATOR.'config.php';
        
        if (file_exists($this->class_loader_path)) {
            require_once $this->class_loader_path;
        }
        
    }
    
    /**
     * Load module configuration
     */
    public function loadConfig()
    {
        if (file_exists($this->config_file_path)) {
            require_once $this->config_file_path;
        }
    }
}
