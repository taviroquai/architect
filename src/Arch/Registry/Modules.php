<?php

namespace Arch\Registry;

/**
 * Description of Modules
 *
 * @author mafonso
 */
class Modules extends \Arch\Registry {
    
    /**
     * Returns a new Modules registry
     */
    public function __construct()
    {
        $this->storage = array();
    }
    
    /**
     * Loads modules on the target path
     * @param string $path The file system path
     */
    public function load($path)
    {
        $modules = glob($path.
                DIRECTORY_SEPARATOR.'enable'.
                DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);

        foreach($modules as $name) {
            $module = new \Arch\Module($name);
            $this->set(dirname($name), $module);
            $module->loadConfig();
        }
    }
}
