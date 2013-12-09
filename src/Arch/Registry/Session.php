<?php

namespace Arch\Registry;

/**
 * Session class
 */
abstract class Session extends \Arch\Registry 
implements \Arch\Messenger
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
     * Returns the session id
     */
    public abstract function getId();

    /**
     * Loads all data into storages
     * Initiates session messages storage
     */
    public abstract function load();
    
    /**
     * Saves all session storage and closes session
     */
    public abstract function save();
    
    /**
     * Resets storage session
     */
    public abstract function reset();
    
    /**
     * Creates a new message
     * @param string $text The message body
     * @param string $cssClass The css class to be used in theme
     * @return \Arch\App The application
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
