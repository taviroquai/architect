<?php
/**
 * Router class
 */
class Router {

    protected $route = array();
    
    public function __construct() {
    
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
    public function addRoute($key, $action) {
        if (!is_callable($action)) return false;
        $this->route[$key] = $action;
        return true;
    }
    
    /**
     * Returns the route action
     * 
     * @param string $key
     * @return boolean|function
     */
    public function getRoute($action) {
        foreach ($this->route as $key => $cb) {
            $match = app()->input->getActionParams($key, $action);
            if ( $match && is_callable($cb)) return $cb;
        }
        return false;
    }
}
