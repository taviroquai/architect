<?php

namespace Arch\Helper;

/**
 * Description of Curl
 *
 * @author mafonso
 */
class Curl extends \Arch\IHelper
{
    protected $url;
    protected $data;
    protected $timeout;
    protected $handler;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
        $this->handler = curl_init();
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handler, CURLOPT_VERBOSE, true);
        $logger = $this->app->getLogger()->getHandler();
        curl_setopt($this->handler, CURLOPT_STDERR, $logger);
        $this->setTimeout(5);
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
    
    public function closeConnection()
    {
        curl_close($this->handler);
    }

    public function execute() {
        curl_setopt($this->handler, CURLOPT_URL, $this->url);
        curl_setopt($this->handler, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        if (!empty($this->data)) {
            curl_setopt($this->handler, CURLOPT_POST, 1);
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($this->data));
        }
        return curl_exec($this->handler);
    }
}
