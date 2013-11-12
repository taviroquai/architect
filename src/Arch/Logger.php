<?php

namespace Arch;

/**
 * Description of Logger
 *
 * @author mafonso
 */
class Logger
{
    
    /**
     * Holds the target file to log messages
     * @var string
     */
    protected $filename;
    
    /**
     * Holds the resource handler
     * @var resource
     */
    protected $handler;
    
    /**
     * Returns a new Logger
     * @param string $filename The file which contains the logs
     */
    public function __construct($filename = '')
    {
        if  (!is_string($filename)) {
            throw new \Exception('Invalid filename');
        }
        $this->filename = $filename;
        
        if (is_file($filename) && is_writable($this->filename)) {
            $this->handler = @fopen($filename, 'a');
        }
    }
    
    /**
     * Logs a message
     * @param string $msg The message to be logged
     * @param string $label The message label ('access', 'error')
     * @param boolean $nlb Tells to add a line break at the end of the message
     * @return boolean
     */
    public function log($msg, $label = 'access', $nlb = false)
    {
        if (!is_resource($this->handler)) return false;
        
        $secs = round(microtime(true)-floor(microtime(true)), 3);
        $time = date('Y-m-d H:i:s').' '.sprintf('%0.3f', $secs).'ms';
        $msg = strtoupper($label).' '.$time.' '.$msg.PHP_EOL;
        if ($nlb) {
            $msg = PHP_EOL.$msg;
        }
        if (is_resource($this->handler)) {
            fwrite($this->handler, $msg);
        }
        return true;
    }
    
    /**
     * Returns true if file is open, or false if not open
     * @return boolean
     */
    public function isOpen()
    {
        return is_resource($this->handler);
    }
    
    /**
     * Closes the resource handler
     * @return boolean
     */
    public function close() {
        if (is_resource($this->handler)) {
            return fclose ($this->handler);
        }
        return true;
    }
}

?>
