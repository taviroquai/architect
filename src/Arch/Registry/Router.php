<?php

namespace Arch\Registry;

/**
 * Router class
 */
class Router extends \Arch\IRegistry
{
    /**
     * Returns a new route registry
     */
    public function __construct()
    {
        $this->storage = array();
        $this->addRoute('/404', function(){});
    }

    /**
     * Adds a route identified by a key
     * Action must be an anonymous function
     * TODO: pass url params to the action callback
     * 
     * @param string $key The route key
     * @param function $action A callable variable
     * @return \Arch\Router
     */
    public function addRoute($key, $action)
    {
        if  (!is_string($key) || empty($key)) {
            throw new \Exception('Invalid route key');
        }
        if (!is_callable($action)) {
            throw new \Exception('Invalid route callback');
        }
        $this->set($key, $action);
        return $this;
    }
    
    /**
     * Returns the route action
     * 
     * @param \Arch\Input $input The application input
     * @return boolean|function
     */
    public function getRouteCallback(\Arch\IInput &$input)
    {
        $result = false;
        foreach ($this->storage as $key => $cb) {
            $params = $input->parseActionParams($key);
            if ( $params !== false && is_callable($cb)) {
                $result = $cb;
                break;
            }
        }
        if ($result === false) {
            if ($this->exists('/404') && is_callable($this->get('/404'))) {
                $result = $this->get('/404');
            }
        }
        return $result;
    }
    
    /**
     * Adds core routes to router
     * @param \Arch\App $app
     */
    public function addCoreRoutes(\Arch\App $app)
    {   
        // Add route 404! Show something if everything else fails...
        $this->addRoute('/404', function() use ($app) {
            $app->getOutput()->setHeaders(
                array('HTTP/1.0 404 Not Found', 'Status: 404 Not Found')
            );
            // set 404 content
            $content = '<h1>404 Not Found</h1>';
            $app->getOutput()->setBuffer($content);
        });
        
        // Add get static core file route
        $this->addRoute(
                '/arch/asset/(:any)/(:any)', 
                function($dir, $filename) use ($app) {
            $filename = implode(
                DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme',$dir,$filename)
            );
            if (!file_exists($filename)) {
                $app->getHelperFactory()->redirect ('/404');
            } else {
                $app->getOutput()->import($filename);
                // add cache headers
                $app->getOutput()->addCacheHeaders();
            }
        });
    }
}
