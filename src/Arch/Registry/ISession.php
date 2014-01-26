<?php

namespace Arch\Registry;

/**
 * Session class
 */
abstract class ISession
extends \Arch\IRegistry 
implements \Arch\IMessenger
{
    /**
     * Holds the session identifier
     * @var string
     */
    public $id;
    
    /**
     * Holds the system messages
     * @var array
     */
    protected $messages;


    /**
     * Returns a new Session
     */
    public function __construct()
    {
        $this->storage = array();
        $this->messages = array();
    }
    
    /**
     * Generates a session ID
     */
    public abstract function generateId();

    /**
     * Loads data into storage
     * Initiates session messages storage
     * @param array $session
     */
    public function load(&$session = array())
    {
        foreach ($session as $prop => $value) {
            if (strpos($prop, 'msgs') === 0) {
                $messages = explode('|', $value);
                foreach ($messages as $msg_serialized) {
                    if ($msg = unserialize($msg_serialized)) {
                        $this->addMessage($msg);
                    }
                }
            } else {
                $this->storage[$prop] = $value;
            }
        }
    }
    
    /**
     * Saves all session storage and close session
     */
    public function save(&$session = array())
    {
        foreach ($this->storage as $prop => $value) {
            $session[$prop] = $value;
        }
        $msgs = $this->getMessages();
        if (count($msgs)) {
            $messages = '';
            foreach ($msgs as $message) {
                $messages[] = serialize($message);
            }
            $session['msgs'] = implode('|', $messages);
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
     * Creates a new message
     * @param string $text The message body
     * @param string $cssClass The css class to be used in theme
     * @return \Arch\Registry\ISession The application
     */
    public function createMessage($text, $cssClass = 'alert alert-success')
    {
        $this->addMessage(new \Arch\Message($text, $cssClass));
        return $this;
    }

    /**
     * Stores a message in session
     * To display messages use: app()->showMessages($template);
     * 
     * @param \Arch\Message $message
     */
    public function addMessage(\Arch\Message $message)
    {
        $this->messages[] = $message;
    }
    
    /**
     * Returns all messages
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * Clears all messages from session
     */
    public function clearMessages() {
        $this->messages = array();
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
}
