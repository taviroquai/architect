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
    public function getRoute($key) {
        if (empty($this->route[$key])) return false;
        return $this->route[$key];
    }
}
