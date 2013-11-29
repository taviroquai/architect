<?php

namespace Arch;

/**
 * Input class
 */
class Input
{
    protected $default = '/';
    protected $api = 'apache';
    protected $httpGet = array();
    protected $httpPost = array();
    protected $httpServer = array();
    protected $params = array();
    protected $files = array();
    protected $raw;
    protected $action;
    protected $base_url = 'http://localhost/';
    protected $index_file = 'index.php';
    
    /**
     * Constructor
     * 
     */
    public function __construct($action = '')
    {
        $this->action = $action;
    }
    
    /**
     * Parse global server input
     * @param string $api
     * @param null|array $get
     * @param null|array $post
     * @param null|array $server
     * @param null|array $files
     * @param null|string $raw
     */
    public function parseGlobal(
        $api    = 'server', 
        $server = array('REQUEST_URI' => '/', 'argv' => array()),
        $base_url = 'http://localhost/',
        $index_file = 'index.php'
    ) {
        $this->api = $api;
        if ($server) $this->httpServer = $server;
        if ($this->isCli()) {
            $this->params = $this->httpServer['argv'];
        } else {
            if (!empty($this->httpGet)) {
                $this->params = array_values($this->httpGet);
            } elseif (!empty($this->httpPost)) {
                $this->params = array_values($this->httpPost);
            }
            $this->base_url = $base_url;
            $this->index_file = $index_file;
        }
    }
    
    /**
     * Returnr whether input is cli or not
     * @return boolean
     */
    public function isCli()
    {
        return $this->api === 'cli' ? true : false;
    }
    
    /**
     * Returns a $_GET param
     * If using command line, will return all parameters
     * 
     * @param string $param
     * @return boolean|string
     */
    public function get($param = null)
    {
        if (empty($param)) {
            return $this->httpGet;
        }
        if (empty($this->httpGet[$param])) {
            return false;
        }
        return $this->httpGet[$param];
    }
    
    /**
     * Returns a $_POST param
     * 
     * @param string $param
     * @return boolean|string
     */
    public function post($param = null)
    {
        if (empty($param)) {
            return $this->httpPost;
        }
        if (empty($this->httpPost[$param])) {
            return false;
        }
        return $this->httpPost[$param];
    }
    
    /**
     * Returns a $_SERVER param
     * 
     * @param string $param
     * @return boolean|string
     */
    public function server($param = null)
    {
        if (empty($param)) {
            return $this->httpServer;
        }
        if (empty($this->httpServer[$param])) {
            return false;
        }
        return $this->httpServer[$param];
    }
    
    /**
     * Return an entry from $_FILES
     * 
     * Example:
     * file(0) will return the first file uploaded result
     * 
     * @param int $index
     * @return boolean
     */
    public function file($index)
    {
        if (empty($this->files[$index])) {
            return false;
        }
        return $this->files[$index];
    }
    
    /**
     * Gets user input action
     * If using command line, will return the first parameter
     * 
     * @return type
     */
    public function getAction()
    {
        // parse action if no action is set
        if (empty($this->action)) {
            $this->action = $this->parseAction($this->default);
        }
        return $this->action;
    }
    
    /**
     * Tries to find user action through all input
     * @param string $action The user action string
     * @return string
     */
    public function parseAction($action)
    {
        // parse action if no action is set
        if (!$this->isCli()) {
            $uri = str_replace(
                array($this->base_url.'/',$this->index_file), 
                '', 
                $this->httpServer['REQUEST_URI']
            );
            $end = strpos($uri, '?') === false ? 
                    strlen($uri) : 
                    strpos($uri, '?');
            $uri = '/'.trim(substr($uri, 0, $end), '/');
            if (!empty($uri)) {
                $action = $uri;
            }
        }
        else {
            if (!empty($this->params[1])) {
                $action = $this->params[1];
            }
        }
        return $action;
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
     * Generates an unique input key
     * @return string
     */
    public function genCacheKey()
    {
        return 'arch.input.'.
                md5($this->action.$this->server('QUERY_STRING'));
    }
    
    /**
     * Check whether it is a core action (arch)
     * @return boolean
     */
    public function isArchAction()
    {
        $params = explode('/', $this->getAction());
        if (empty($params[1])) {
            return false;
        }
        if ($params[1] !== 'arch') {
            return false;
        }
        return true;
    }
    
    /**
     * Returns a param by index
     * If index is not provided, returns all params
     * 
     * @param integer $index
     * @return boolean
     */
    public function getParam($index = null)
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
     * Sets the SERVER variables
     * @param array $array
     */
    public function setHttpServer($array)
    {
        $this->httpServer = $array;
    }
    
    /**
     * Sets the HTTP GET params
     * @param array $array
     */
    public function setHttpGet($array)
    {
        $this->httpGet = $array;
    }
    
    /**
     * Sets the HTTP POST params
     * @param array $array
     */
    public function setHttpPost($array)
    {
        $this->httpPost = $array;
    }
    
    /**
     * Loads HTTP FILES information
     * @param array $array
     */
    public function setHttpFiles($array)
    {
        $this->remapFiles($array);
    }
    
    /**
     * Sets the raw input (usually php://input)
     * @param string $raw
     */
    public function setRawInput($raw)
    {
        $this->raw = $raw;
    }
    
    private function remapFiles($files)
    {
        $files = reset($files);
        if (is_array($files['name'])) {
            $new = array();
            foreach( $files as $key => $all ){
                foreach( $all as $i => $val ){
                    $new[$i][$key] = $val;    
                }    
            }
            $this->files = $new;
        }
        else {
            $file_keys = array_keys($files);
            foreach ($file_keys as $key) {
                $this->files[0][$key] = $files[$key];
            }
        }
    }
}
