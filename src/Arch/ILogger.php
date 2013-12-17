<?php

namespace Arch;

/**
 * Description of ILogger
 *
 * @author mafonso
 */
abstract class ILogger
{
    /**
     * Holds the log messages
     * @var array
     */
    protected $messages = array();
    
    /**
     * Logs a message
     * @param string $msg The message to be logged
     * @param string $label The message label ('access', 'error')
     * @param boolean $nlb Tells to add a line break at the end of the message
     * @return \Arch\ILogger
     */
    public function log($msg)
    {
        $this->messages[] = $msg;
        return $this;
    }
    
    /**
     * Tells the logger to dump messages
     */
    public abstract function dumpMessages();
}
