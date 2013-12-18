<?php

namespace Arch\Registry\Session;

/**
 * File session class
 */
class File extends \Arch\Registry\ISession
{
    protected $path;
    
    public function __construct($path = '') {
        parent::__construct();
        $this->path = (string) $path;
    }
    
    /**
     * Returns the session path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Returns the session filename
     * @return string
     */
    public function getFilename()
    {
        return $this->path.DIRECTORY_SEPARATOR.$this->id;
    }
    
    /**
     * Sets the session path
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = (string) $path;
    }

    /**
     * Generates a session identifier
     */
    public function generateId($id = '') {
        $this->id = empty($id) ? md5(time()) : $id;
    }

    /**
     * Loads data into storage
     * Initiates session messages storage
     * @throws \Exception
     */
    public function load(&$session = array())
    {
        $filename = $this->getFilename();
        if (!is_writable(dirname($filename))) {
            throw new \Exception('Invalid session filename');
        }
        if (!file_exists($filename)) {
            touch($filename);
            file_put_contents($filename, serialize(array()));
        }
        $session = unserialize(file_get_contents($filename));
        parent::load($session);
    }
    
    /**
     * Saves all session storage and close session
     */
    public function save(&$session = array())
    {
        parent::save($session);
        file_put_contents($this->getFilename(), serialize($session));
    }
    
    /**
     * Resets storage session
     */
    public function reset()
    {
        parent::reset();
        if (file_exists($this->getFilename())) {
            unlink($this->getFilename());
        }
        $this->generateId();
        $this->load();
    }
}
