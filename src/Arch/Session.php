<?php

namespace Arch;

/**
 * Session class
 * This uses native PHP $_SESSION
 */
class Session implements Messenger
{
    protected $storage;
    protected $app;
    
    public function __construct(\Arch\App &$app)
    {
        $this->app = $app;
    }

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
        
        // trigger core event
        $this->app->triggerEvent('arch.session.after.load', $this);
        
        $this->app->log('Session loaded');
    }
    
    /**
     * Saves current storage to $_SESSION and close session
     */
    public function save()
    {
        foreach ($this->storage as $prop => $value) {
            $_SESSION[$prop] = $value;
        }
        
        // trigger core event
        \Arch\App::Instance()
                ->triggerEvent('arch.session.after.save', $_SESSION);
        
        // finally close session
        session_write_close();
    }

    /**
     * Destroys $_SESSION and reset storage
     */
    public function destroy()
    {
        $this->storage = array();
        session_destroy();
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
        return isset($this->storage[$name]);
    }

    public function __unset($name) {
        unset($this->storage[$name]);
    }
}
