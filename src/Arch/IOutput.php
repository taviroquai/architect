<?php

namespace Arch;

/**
 * Output class
 */
abstract class IOutput
{
    /**
     * Holds the output buffer
     * @var string
     */
    protected $buffer;
    
    /**
     * Returns a new Output Object
     * 
     * @param string $content The output content
     */
    public function __construct($buffer = '')
    {
        $this->buffer = $buffer;
    }
    
    /**
     * Sets the output buffer
     * 
     * @param string $buffer The content to be sent
     * @return \Arch\Output
     */
    public function setBuffer($content)
    {
        $this->buffer = (string) $content;
        return $this;
    }
    
    /**
     * Returns the output content
     * @return string
     */
    public function & getBuffer()
    {
        return $this->buffer;
    }
    
    /**
     * Send the output
     */
    abstract public function send();
    
    /**
     * Outputs a static file
     * @param string $filename
     */
    public function import($filename) {
        
        if (!file_exists($filename)) {
            return false;
        }
        
        // load file contents
        $this->setBuffer(file_get_contents($filename));
        return true;
    }
}
