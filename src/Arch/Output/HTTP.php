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
        $headers = (array) $headers;
        foreach ($headers as $item) {
            $this->addHeader($item);
        }
        return $this;
    }
    
    /**
     * Adds an HTTP header
     * @param string $header
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
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
        $this->addHeader("Expires: $ts");
        $this->addHeader("Pragma: cache");
        $this->addHeader("Cache-Control: max-age=".$seconds);
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
