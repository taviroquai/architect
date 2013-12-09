<?php

namespace Arch\Input;

/**
 * Description of HTTP
 *
 * @author mafonso
 */
class HTTP extends \Arch\Input {
    
    /**
     * Holds the HTTP request method
     * @var string
     */
    protected $method;
    
    /**
     * Holds the request URI
     * @var string
     */
    protected $uri;
    
    /**
     * Holds the HTTP user agent (UA)
     * @var string
     */
    protected $user_agent;
    
    /**
     * Holds the HTTP query string
     * @var string
     */
    protected $query;
    
    /**
     * Holds the request host
     * @var string
     */
    protected $host;
    
    /**
     * Tells if if is a secure HTTP request
     * @var https
     */
    protected $https;
    
    /**
     * The list of input params
     * @var array
     */
    protected $params = array();
    
    /**
     * Returns a new HTTP input
     * @param string $method
     * @param string $api
     */
    public function __construct($method)
    {
        parent::__construct();
        $this->method = $method;
        $this->api = 'apache2handler';
    }
    
    /**
     * Tries to find user action through all input
     * @param string $base_url The application base url
     * @param string $index_file The application index filename
     */
    public function parseAction($base_url = '/', $index_file = 'index.php')
    {
        $uri = str_replace(
            array($base_url.'/',$index_file), 
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
     * Sets the input params
     * @param array $params The input params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    /**
     * Returns a param by index
     * If index is not provided, returns all params
     * 
     * @param integer $index
     * @return boolean
     */
    public function get($index = null)
    {
        if ($index === null) {
            return $this->params;
        }
        if (!isset($this->params[$index])) {
            return false;
        }
        return $this->params[$index];
    }
    
    /**
     * Sets the input api
     * @param string $api
     */
    public function setAPI($api)
    {
        $this->api = $api;
    }
    
    public function setRequestUri($uri)
    {
        $this->uri = (string) $uri;
    }
    
    public function setQueryString($query)
    {
        $this->query = (string) $query;
    }
    
    public function setUserAgent($ua)
    {
        $this->user_agent = (string) $ua;
    }
    
    public function setHttpHost($host)
    {
        $this->host = $host;
    }
    
    public function setSecure($boolean)
    {
        $this->https = (boolean) $boolean;
    }

    public function getMethod()
    {
        return $this->method;
    }
    
    public function getRequestUri()
    {
        return $this->uri;
    }
    
    public function getUserAgent()
    {
        return $this->user_agent;
    }
    
    public function getHttpHost()
    {
        return $this->host;
    }
    
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
