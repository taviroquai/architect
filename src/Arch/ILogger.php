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
     * @return \Arch\ILogger
     */
    public function log($msg, $label = 'access')
    {
        $this->messages[] = implode(' ', array($label,$msg));
        return $this;
    }
    
    /**
     * Tells the logger to dump messages
     */
    public abstract function dumpMessages();
}
