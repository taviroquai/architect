<?php

namespace Arch\Input;

/**
 * Description of CLI
 *
 * @author mafonso
 */
class CLI extends \Arch\Input
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
     * @param string $base_url The application base url
     * @param string $index_file The application index filename
     */
    public function parseAction($base_url = '/', $index_file = 'index.php')
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
}
