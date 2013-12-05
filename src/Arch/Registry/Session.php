<?php

namespace Arch\Registry;

/**
 * Session class
 */
class Session extends \Arch\Registry
{
    /**
     * Holds the session identifier
     * @var string
     */
    public $name;
    
    /**
     * Returns a new Session
     */
    public function __construct()
    {
        $this->storage = array();
        $this->name = 'arch-'.md5(time());
    }

    /**
     * Loads data into storage
     * Initiates session messages storage
     */
    public function load($data)
    {
        foreach ($data as $prop => $value) {
            if (
                    strpos($prop, 'user.') === false
                    && strpos($prop, 'arch.') === false
            ) {
                $prop = 'user.'.$prop;
            }
            $this->storage[$prop] = $value;
        }
        
        if (!isset($this->storage['arch.message'])) {
            $this->storage['arch.message'] = array();
        }
    }
    
    /**
     * Saves current storage to $data and close session
     */
    public function save(&$data)
    {
        $data = array();
        foreach ($this->storage as $prop => $value) {
            if (strpos($prop, 'user.') === 0) {
                $prop = substr($prop, 5);
            }
            $data[$prop] = $value;
        }
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
     * To display messages use: app()->showMessages($template);
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
    
    /**
     * Loads an array of messages
     * @param array $messages
     */
    public function loadMessages($messages)
    {
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->addMessage($message);
            }
        }
    }
    
    public function get($prop) {
        if (!$this->exists($prop)) {
            return null;
        }
        $prop = 'user.'.$prop;
        $value = @unserialize($this->storage[$prop]);
        if ($value !== false) {
            return $value;
        }
        return $this->storage[$prop];
    }
    
    public function set($prop, $value) {
        $prop = 'user.'.$prop;
        if (is_array($value) || is_object($value)) {
            parent::set($prop, serialize($value));
        } else {
            parent::set($prop, $value);
        }
    }
    
    public function exists($prop) {
        $prop = 'user.'.$prop;
        return parent::exists($prop);
    }

    public function delete($prop) {
        $prop = 'user.'.$prop;
        parent::delete($prop);
    }
}
