<?php

namespace Arch\Logger;

/**
 * Description of File logger
 *
 * @author mafonso
 */
class File extends \Arch\ILogger
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
     * Sets the filename
     * @param string $filename
     */
    public function setFilename($filename)
    {
        if  (!is_string($filename)) {
            throw new \Exception('Invalid filename');
        }
        $this->filename = $filename;
        $this->open();
    }

    /**
     * Logs a message
     * @param string $msg The message to be logged
     * @param string $label The message label ('access', 'error')
     * @param boolean $nlb Tells to add a line break at the end of the message
     */
    public function log($msg, $label = 'access', $nlb = false)
    {
        $secs = round(microtime(true)-floor(microtime(true)), 3);
        $time = date('Y-m-d H:i:s').' '.sprintf('%0.3f', $secs).'ms';
        $msg = strtoupper($label).' '.$time.' '.$msg.PHP_EOL;
        if ($nlb) {
            $msg = PHP_EOL.$msg;
        }
        return parent::log($msg);
    }
    
    /**
     * Opens the file handler
     */
    public function open()
    {
        if (!$this->isOpen()) {
            $this->handler = @fopen($this->filename, 'a');
        }
    }

    /**
     * Returns true if file is open, or false if not open
     * @return boolean
     */
    public function isOpen()
    {
        return is_resource($this->getHandler());
    }
    
    /**
     * Closes the resource handler
     * @return boolean
     */
    public function close()
    {
        $result = false;
        if ($this->isOpen()) {
            $result = fclose ($this->getHandler());
        }
        return $result;
    }
    
    /**
     * Returns the logger handler
     * @return resource
     */
    public function getHandler()
    {
        return $this->handler;
    }
    
    /**
     * Dumps the log messages
     */
    public function dumpMessages() {
        $this->open();
        if ($this->isOpen()) {
            foreach ($this->messages as $msg) {
                fwrite($this->getHandler(), $msg);
            }
        }
    }
}
