<?php

namespace Arch;

/**
 * Session class
 * This uses native PHP $_SESSION
 */
class Session implements Messenger
{
    protected $storage;
    
    /**
     * Loads data from $_SESSION into storage
     * Initiates _message and login values
     */
    public function load()
    {
        $this->storage = array();
        if (empty($this->storage)) {
            session_start();
        }
        if (!isset($this->storage['_message'])) {
            $this->storage['_message'] = array();
        }
        if (empty($_SESSION['login'])) {
            $_SESSION['login'] = null;
        }
        $this->storage = $_SESSION;
        \Arch\App::Instance()->log('Session loaded');
    }
    
    /**
     * Saves current storage to $_SESSION and close session
     */
    public function save()
    {
        foreach ($this->storage as $prop => $value) {
            $_SESSION[$prop] = $value;
        }
        session_write_close();
        \Arch\App::Instance()->log('Session closed');
    }

    /**
     * Destroys $_SESSION and reset storage
     */
    public function destroy()
    {
        $this->storage = array();
        session_destroy();
        \Arch\App::Instance()->log('Session destroyed');
    }
    
    /**
     * Adds a screen message
     * To display messages use: \Arch\App::Instance()->showMessages($template);
     * 
     * @param string $text
     * @param string $cssClass
     */
    public function addMessage($text, $cssClass = 'alert alert-success')
    {
        $this->storage['_message'][] = new Message($text, $cssClass);
    }
    
    /**
     * Returns all messages
     * @return array
     */
    public function getMessages()
    {
        if (!isset($this->storage['_message'])) {
            $this->storage['_message'] = array();
        }
        return $this->storage['_message'];
    }
    
    /**
     * Clears all messages from session
     */
    public function clearMessages() {
        $this->storage['_message'] = array();
    }
    
    public function __get($prop) {
        if (!isset($this->storage[$prop])) {
            return null;
        }
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
