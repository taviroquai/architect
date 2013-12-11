<?php

namespace Arch\Registry\Session;

/**
 * Native session class
 */
class Native extends \Arch\Registry\ISession
{
    /**
     * Generates a session identifier
     */
    public function generateId() {
        session_regenerate_id();
        $this->id = session_id();
    }

    /**
     * Loads data into storage
     * Initiates session messages storage
     */
    public function load(&$session = array())
    {
        ini_set('session.gc_probability', 0);
        session_start();
        parent::load($_SESSION);
    }
    
    /**
     * Saves all session storage and close session
     */
    public function save(&$session = array())
    {
        $_SESSION = array();
        parent::save($_SESSION);
        session_write_close();
    }
    
    /**
     * Resets storage session
     */
    public function reset()
    {
        parent::reset();
        session_destroy();
        $this->load();
    }
}
