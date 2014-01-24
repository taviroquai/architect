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
     * @param string $buffer The output content
     */
    public function __construct($buffer = '')
    {
        $this->buffer = $buffer;
    }
    
    /**
     * Sets the output buffer
     * 
     * @param string $content The content to be sent
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
     * Adds an HTTP header
     * @param string $header
     */
    public abstract function addHeader($header);
    
    /**
     * Sets HTTP headers to be used on HTTP type
     * @param array $headers The list of headers to be sent
     * @return \Arch\Output
     */
    public abstract function setHeaders($headers);

    /**
     * Returns the output headers
     * @return array
     */
    public abstract function getHeaders();
    
    /**
     * Adds cache headers
     */
    public abstract function addCacheHeaders($seconds = 300);
    
    /**
     * Send the output
     */
    public abstract function send();
    
    /**
     * Outputs a static file
     * @param string $filename
     */
    public function import($filename) {
        
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        
        // load file contents
        $this->setBuffer(file_get_contents($filename));
        return true;
    }
}
