<?php

namespace Arch;

/**
 * Session class
 */
class Session
{
    /**
     * Holds a list of values
     * @var array
     */
    protected $storage;
    
    /**
     * Returns a new Session
     */
    public function __construct()
    {
        $this->storage = array();
    }
    
    /**
     * Clears the storage
     */
    public function __destruct()
    {
        unset($this->storage);
    }

    /**
     * Loads data from $_SESSION into storage
     * Initiates _message and login values
     */
    public function load($data = array())
    {
        $this->storage = $data;
        if (!isset($this->storage['arch.message'])) {
            $this->storage['arch.message'] = array();
        }
    }
    
    /**
     * Saves current storage to $_SESSION and close session
     */
    public function save(&$data = array())
    {
        foreach ($data as $prop => &$value) {
            unset($value);
        }
        foreach ($this->storage as $prop => $value) {
            $data[$prop] = $value;
        }
    }
    
    /**
     * Stores a message in session
     * To display messages use: \Arch\App::Instance()->showMessages($template);
     * 
     * @param \Arch\Message $message
     */
    public function addMessage(\Arch\Message $message)
    {
        $this->storage['arch.message'][] = $message;
    }
    
    /**
     * Returns all messages
     * @return array
     */
    public function getMessages()
    {
        if (!isset($this->storage['arch.message'])) {
            $this->storage['arch.message'] = array();
        }
        return $this->storage['arch.message'];
    }
    
    /**
     * Clears all messages from session
     */
    public function clearMessages() {
        unset($this->storage['arch.message']);
        $this->storage['arch.message'] = array();
    }
    
    public function __get($prop) {
        $prop = 'user.'.$prop;
        if (!isset($this->storage[$prop])) {
            return null;
        }
        $value = @unserialize($this->storage[$prop]);
        if ($value !== false) {
            return $value;
        }
        return $this->storage[$prop];
    }
    
    public function __set($prop, $value) {
        $prop = 'user.'.$prop;
        if (is_array($value) || is_object($value)) {
            $this->storage[$prop] = serialize($value);
        } else {
            $this->storage[$prop] = $value;
        }
    }
    
    public function __isset($prop) {
        $prop = 'user.'.$prop;
        return isset($this->storage[$prop]);
    }

    public function __unset($prop) {
        $prop = 'user.'.$prop;
        unset($this->storage[$prop]);
    }
}
