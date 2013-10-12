<?php

class Router {

    protected $route = array();
    
    public function __construct() {
    
    }
    
    public function addRoute($key, $action) {
        $this->route[$key] = $action;
    }
    
    public function getRoute($key) {
        if (empty($this->route[$key])) return false;
        return $this->route[$key];
    }
}
