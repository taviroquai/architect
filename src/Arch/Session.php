<?php

namespace Arch;

/**
 * Session class
 */
class Session
{
    /**
     * Holds the session identifier
     * @var string
     */
    public $name;
    
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
        $this->name = 'arch-'.md5(time());
    }

    /**
     * Loads data from $_SESSION into storage
     * Initiates session messages storage
     */
    public function load()
    {
        if (isset($_SESSION)) {
            foreach ($_SESSION as $prop => $value) {
                if (
                        strpos($prop, 'user.') === false
                        && strpos($prop, 'arch.') === false
                ) {
                    $prop = 'user.'.$prop;
                }
                $this->storage[$prop] = $value;
            }
        }
        
        if (!isset($this->storage['arch.message'])) {
            $this->storage['arch.message'] = array();
        }
    }
    
    /**
     * Saves current storage to $data and close session
     */
    public function save()
    {
        $_SESSION = array();
        foreach ($this->storage as $prop => $value) {
            if (strpos($prop, 'user.') === 0) $prop = substr($prop, 5);
            $_SESSION[$prop] = $value;
        }
        return $_SESSION;
    }
    
    /**
     * Resets storage session
     */
    public function reset()
    {
        foreach ($this->storage as $prop => $value) {
            unset($this->storage[$prop]);
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
