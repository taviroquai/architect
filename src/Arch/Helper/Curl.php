<?php

namespace Arch\Helper;

/**
 * Description of Curl
 *
 * @author mafonso
 */
class Curl extends \Arch\IHelper
{
    /**
     * Holds the url to be called
     * @var string
     */
    protected $url;
    
    /**
     * Holds the data items (kvp) to be sent
     * @var array
     */
    protected $data;
    
    /**
     * Holds the timeout value to wait for the response
     * @var int
     */
    protected $timeout;
    
    /**
     * Holds the cURL handler
     * @var resource
     */
    protected $handler;
    
    /**
     * Returns a new URL helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, 1);
        $this->setTimeout(5);
    }
    
    /**
     * Sets the URL to be called
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }
    
    /**
     * Sets the data to be sent (kvp)
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = (array) $data;
    }
    
    /**
     * Sets the timeout value to wait for the response
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;
    }
    
    /**
     * Closed the cURL handler connection
     */
    public function closeConnection()
    {
        curl_close($this->handler);
    }

    /**
     * Returns the URL body response
     * @return string
     */
    public function run() {
        curl_setopt($this->handler, CURLOPT_URL, $this->url);
        curl_setopt($this->handler, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        if (!empty($this->data)) {
            curl_setopt($this->handler, CURLOPT_POST, 1);
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
        }
        return curl_exec($this->handler);
    }
}
