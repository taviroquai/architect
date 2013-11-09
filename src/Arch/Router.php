<?php

namespace Arch;

/**
 * Router class
 */
class Router
{
    /**
     * Holds the list of routes
     * @var array
     */
    protected $route;
    
    /**
     * Returns a new router
     */
    public function __construct()
    {
        $this->route = array();
    }
    
    /**
     * Adds a route identified by a key
     * Action must be an anonymous function
     * TODO: pass url params to the action callback
     * 
     * @param string $key The route key
     * @param function $action A callable variable
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
     * @param string $key The route key
     * @param \Arch\Input $input The input provider
     * @return boolean|function
     */
    public function getRoute($action, \Arch\Input $input)
    {
        foreach ($this->route as $key => $cb) {
            $match = $input->getActionParams($key, $action);
            if ( $match && is_callable($cb)) {
                return $cb;
            }
        }
        return false;
    }
}
