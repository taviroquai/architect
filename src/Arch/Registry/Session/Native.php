<?php

namespace Arch\Registry\Session;

/**
 * Native session class
 */
class Native extends \Arch\Registry\Session
{
    /**
     * Generates a session identifier
     */
    public function generateId() {
        session_regenerate_id();
        $this->id = session_id();
    }

    /**
     * Returns the session id
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Loads data into storage
     * Initiates session messages storage
     */
    public function load()
    {
        ini_set('session.gc_probability', 0);
        session_start();
        foreach ($_SESSION as $prop => $value) {
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
    public function save()
    {
        $_SESSION = array();
        foreach ($this->storage as $prop => $value) {
            $_SESSION[$prop] = $value;
        }
        $msgs = $this->getMessages();
        if (count($msgs)) {
            $messages = '';
            foreach ($msgs as $message) {
                $messages[] = serialize($message);
            }
            $_SESSION['msgs'] = implode('|', $messages);
        }
        session_write_close();
    }
    
    /**
     * Resets storage session
     */
    public function reset()
    {
        foreach ($this->storage as $prop => $value) {
            unset($this->storage[$prop]);
        }
        session_destroy();
        $this->load();
    }
}
