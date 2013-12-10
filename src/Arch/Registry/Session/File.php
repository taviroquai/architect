<?php

namespace Arch\Registry\Session;

/**
 * File session class
 */
class File extends \Arch\Registry\ISession
{
    /**
     * Generates a session identifier
     */
    public function generateId() {
        $this->id = md5(time());
    }

    /**
     * Loads data into storage
     * Initiates session messages storage
     */
    public function load(&$session = array())
    {
        parent::load($session);
    }
    
    /**
     * Saves all session storage and close session
     */
    public function save(&$session = array())
    {
        parent::save($session);
    }
    
    /**
     * Resets storage session
     */
    public function reset()
    {
        parent::reset();
        $this->load();
    }
}
