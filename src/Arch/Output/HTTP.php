<?php

namespace Arch\Output;

/**
 * HTTP output class
 */
class HTTP extends \Arch\IOutput
{
    /**
     * Holds the HTTP headers
     * @var array
     */
    protected $headers = array();
    
    /**
     * Sets HTTP headers to be used on HTTP type
     * @param array $headers The list of headers to be sent
     * @return \Arch\Output
     */
    public function setHeaders($headers)
    {
        $this->headers = (array) $headers;
        return $this;
    }
    
    /**
     * Returns the output headers
     * @return array
     */
    public function & getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Adds cache headers
     */
    public function addCacheHeaders($seconds = 300)
    {
        $ts = gmdate("D, d M Y H:i:s", time()+$seconds)." GMT";
        $this->headers[] = "Expires: $ts";
        $this->headers[] = "Pragma: cache";
        $this->headers[] = "Cache-Control: max-age=".$seconds;
    }

    /**
     * Sends HTTP Headers
     */
    public function send()
    {
        foreach ($this->headers as $item) {
            header($item);
        }
    }
    
}
