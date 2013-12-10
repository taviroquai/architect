<?php

namespace Arch\Input;

/**
 * Description of CLI
 *
 * @author mafonso
 */
class CLI extends \Arch\IInput
{
    /**
     * Returns a new CLI Input
     */
    public function __construct()
    {
        parent::__construct();
        $this->api = 'cli';
    }
    
    /**
     * Tries to find user action through all input
     * @param \Arch\Registry\Config $config The application configuration
     */
    public function parseAction(\Arch\Registry\Config $config)
    {
        if (!empty($this->params[1])) {
            $this->action = $this->params[1];
        }
    }
    
    /**
     * Gets properties from $_SERVER
     * @param array $server
     */
    public function parseServer($server)
    {
        $this->params = $server['argv'];
        $this->user_agent = $server['SHELL'];
    }
    
    /**
     * Tells whether or not is a CLI input
     * @return boolean
     */
    public function isCli()
    {
        return true;
    }
    
    /**
     * Returns an uploaded file or false if does not exists
     * @param $index The uploaded file index
     * @return boolean
     */
    public function getFileByIndex($index)
    {
        return false;
    }
    
    /**
     * Returns the input agent
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }
}
