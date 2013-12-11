<?php

namespace Arch\Output;

/**
 * Raw output class
 */
class Raw extends \Arch\IOutput
{
    /**
     * Adds an HTTP header
     * @param string $header
     */
    public function addHeader($header) {}
    
    /**
     * Sets HTTP headers to be used on HTTP type
     * @param array $headers The list of headers to be sent
     * @return \Arch\Output
     */
    public function setHeaders($headers) {}

    /**
     * Returns the output headers
     * @return array
     */
    public function getHeaders() {}
    
    /**
     * Adds cache headers
     */
    public function addCacheHeaders($seconds = 300) {}
            
    /**
     * Send the output
     */
    public function send()
    {
        echo $this->getBuffer();
    }
}
