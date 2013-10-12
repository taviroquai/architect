<?php

class Session implements Messenger {

    protected $storage;
    
    public function load() {
        $this->storage = array();
        if (empty($storage)) session_start();
        if (!isset($this->storage['_message'])) $this->storage['_message'] = array();
        if (empty($_SESSION['login'])) $_SESSION['login'] = null;
        $this->storage = $_SESSION;
    }
    
    public function save() {
        foreach ($this->storage as $prop => $value) $_SESSION[$prop] = $value;
        session_write_close();
    }

    public function destroy() {
        foreach ($properies as $prop) $this->storage = array();
        session_destroy();
    }
    
    public function addMessage($text, $cssClass = 'alert alert-success') {
        $this->storage['_message'][] = new Message($text, $cssClass);
    }
    
    public function getMessages() {
        if (!isset($this->storage['_message'])) $this->storage['_message'] = array();
        return $this->storage['_message'];
    }
    
    public function clearMessages() {
        $this->storage['_message'] = array();
    }
    
    public function __get($prop) {
        if (!isset($this->storage[$prop])) return null;
        return $this->storage[$prop];
    }
    
    public function __set($prop, $value) {
        $this->storage[$prop] = $value;
    }
    
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __unset($name) {
        unset($this->data[$name]);
    }
    
}
