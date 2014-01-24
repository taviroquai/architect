<?php

namespace Arch\Input;

/**
 * Description of HTTP
 *
 * @author mafonso
 */
class HTTP extends \Arch\IInput {
    
    /**
     * Holds the HTTP request method
     * @var string
     */
    protected $method;
    
    /**
     * Holds the HTTP query string
     * @var string
     */
    protected $query;
    
    /**
     * Tells if if is a secure HTTP request
     * @var https
     */
    protected $https;
    
    /**
     * Returns a new HTTP input
     * @param string $method
     */
    public function __construct($method)
    {
        parent::__construct();
        $this->method = $method;
        $this->api = 'apache2handler';
    }
    
    /**
     * Tries to find user action through all input
     * @param \Arch\Registry\Config $config The application configuration
     */
    public function parseAction(\Arch\Registry\Config $config)
    {
        $uri = str_replace(
            array($config->get('BASE_URL').'/',$config->get('INDEX_FILE')), 
            '', 
            $this->uri
        );
        $end = strpos($uri, '?') === false ? 
                strlen($uri) : 
                strpos($uri, '?');
        $uri = '/'.trim(substr($uri, 0, $end), '/');
        if (!empty($uri)) {
            $this->action = $uri;
        }
    }
    
    /**
     * Gets properties from $_SERVER
     * @param array $server
     */
    public function parseServer($server)
    {
        $this->method = $server['REQUEST_METHOD'];
        $this->uri = $server['REQUEST_URI'];
        $this->query = $server['QUERY_STRING'];
        $this->user_agent = isset($server['HTTP_USER_AGENT']) ? 
                $server['HTTP_USER_AGENT'] : '';
        $this->host = $server['HTTP_HOST'];
        $this->https = isset($server['HTTPS']) ? true : false;
    }

    /**
     * Generates an unique input key
     * @return string
     */
    public function genCacheKey()
    {
        return 'arch.input.'.md5($this->action.$this->query);
    }
    
    /**
     * Sets the input api
     * @param string $api
     */
    public function setAPI($api)
    {
        $this->api = $api;
    }
    
    /**
     * Sets the request uri
     * @param string $uri The uri string
     */
    public function setRequestUri($uri)
    {
        $this->uri = (string) $uri;
    }
    
    /**
     * Sets the HTTP query string
     * @param string $query The query string
     */
    public function setQueryString($query)
    {
        $this->query = (string) $query;
    }
    
    /**
     * Sets the user agent
     * @param string $ua The user agent string
     */
    public function setUserAgent($ua)
    {
        $this->user_agent = (string) $ua;
    }
    
    /**
     * Sets the HTTP hostname
     * @param string $host The host name
     */
    public function setHttpHost($host)
    {
        $this->host = $host;
    }
    
    /**
     * Sets whether the request is secure HTTPS or not
     * @param boolean $boolean The true or false
     */
    public function setSecure($boolean)
    {
        $this->https = (boolean) $boolean;
    }

    /**
     * Returns the HTTP method
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Returns the URI request
     * @return string
     */
    public function getRequestUri()
    {
        return $this->uri;
    }
    
    /**
     * Returns the request user agent
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }
    
    /**
     * Returns the HTTP hostname
     * @return string
     */
    public function getHttpHost()
    {
        return $this->host;
    }
    
    /**
     * Returns whether the request is secure or not
     * @return boolean
     */
    public function isSecure()
    {
        return $this->https;
    }
    
    /**
     * Tells whether or not is a CLI input
     * @return boolean
     */
    public function isCli()
    {
        return false;
    }
    
    /**
     * Returns an uploaded file or false if does not exists
     * @param $index The uploaded file index
     * @return boolean
     */
    public function getFileByIndex($index)
    {
        return false;
    }
}
