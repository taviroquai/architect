<?php

namespace Arch;

/**
 * Description of IHelper
 *
 * @author mafonso
 */
abstract class IHelper {
    
    /**
     * Holds the Architect application
     * @var \Arch\App
     */
    protected $app;
    
    /**
     * Returns a new application helper
     * @param \Arch\App $app The application
     */
    public function __construct(\Arch\App $app) {
        $this->app = $app;
    }

    /**
     * Executes the helper
     */
    public abstract function execute();
}
