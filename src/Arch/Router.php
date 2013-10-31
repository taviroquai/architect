<?php

namespace Arch;

/**
 * Router class
 */
class Router
{
    protected $route = array();
    
    public function __construct(\Arch\App $app)
    {
        // Add route 404! Show something if everything else fails...
        $this->addRoute('/404', function() use ($app) {
            $app->output->setHeaders(
                array('HTTP/1.0 404 Not Found', 'Status: 404 Not Found')
                );
            
            // set 404 content
            $content = '<h1>404 Not Found</h1>';
            $app->output->setContent($content);
        });
        
        $this->addRoute('/arch/asset/(:any)/(:any)', 
                function($dir, $filename) use ($app) {
            $filename = ARCH_PATH.DIRECTORY_SEPARATOR.
                    'theme'.DIRECTORY_SEPARATOR.
                    'architect'.DIRECTORY_SEPARATOR.
                     $dir.DIRECTORY_SEPARATOR.$filename;
            if (!file_exists($filename)) $app->redirect ('/404');
            else {
                $app->output->readfile($filename);
            }
        });
    }
    
    /**
     * Adds a route identified by a key
     * Action must be an anonymous function
     * TODO: pass url params to the action callback
     * 
     * @param string $key
     * @param function $action
     * @return boolean
     */
    public function addRoute($key, $action)
    {
        if (!is_callable($action)) {
            return false;
        }
        $this->route[$key] = $action;
        return true;
    }
    
    /**
     * Returns the route action
     * 
     * @param string $key
     * @return boolean|function
     */
    public function getRoute($action)
    {
        foreach ($this->route as $key => $cb) {
            $match = \Arch\App::Instance()->input->
                    getActionParams($key, $action);
            if ( $match && is_callable($cb)) {
                return $cb;
            }
        }
        return false;
    }
}
