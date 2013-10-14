<?php

/**
 * Input class
 */
class Input {

    protected $files = array();
    protected $raw;
    protected $api;
    protected $params = array();
    protected $action;
    
    /**
     * Constructor
     * Default $_GET, $_POST, $_FILES, $_SERVER and 'php://input' is parsed
     */
    public function __construct() {
        
        $this->api = php_sapi_name();
        if (!empty($_FILES)) $this->remapFiles($_FILES);
        $this->raw = file_get_contents("php://input");
        $this->getAction();
        app()->log('Input finish loading: '.$this->server('HTTP_USER_AGENT'));
    }
    
    /**
     * Returnr whether input is cli or not
     * @return boolean
     */
    public function isCli() {
        return $this->api === 'cli' ? true : false;
    }
    
    /**
     * Returns a $_GET param
     * If using command line, will return all parameters
     * 
     * @param string $param
     * @return boolean|string
     */
    public function get($param = null) {
        if ($this->api == 'cli') return $this->params;
        if (empty($param)) return $_GET;
        if (empty($_GET[$param])) return false;
        return $_GET[$param];
    }
    
    /**
     * Returns a $_POST param
     * 
     * @param string $param
     * @return boolean|string
     */
    public function post($param = null) {
        if (empty($param)) return $_POST;
        if (empty($_POST[$param])) return false;
        return $_POST[$param];
    }
    
    /**
     * Returns a $_SERVER param
     * 
     * @param string $param
     * @return boolean|string
     */
    public function server($param = null) {
        if (empty($param)) return $_SERVER;
        if (empty($_SERVER[$param])) return false;
        return $_SERVER[$param];
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
    public function file($index) {
        if (empty($this->files[$index])) return false;
        return $this->files[$index];
    }
    
    /**
     * Gets user input action
     * If using command line, will return the first parameter
     * 
     * @return type
     */
    public function getAction() {
        
        // parse action if no action is set
        if (empty($this->action)) {
            $this->action = '/';
            if ($this->api != 'cli') {
                $uri = str_replace(array(BASEURL,INDEXFILE), '', $_SERVER['REQUEST_URI']);
                $end = strpos($uri, '?') === false ? strlen($uri) : strpos($uri, '?');
                $uri = substr($uri, 0, $end);
                $uri = '/'.trim($uri, '/');
                if (!empty($uri)) $this->action = $uri;
            }
            else {
                $this->params = $_SERVER['argv'];
                if (!empty($this->params[1])) $this->action = $this->params[1];
            }
        }
        return $this->action;
    }
    
    /**
     * Returns true or false if pattern matches action
     * Matches are populated in $this->params
     * 
     * @param string $pattern
     * @param string $action
     * @return boolean
     */
    public function getActionParams($pattern, $action) {
        $pattern = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $pattern);
        $match = preg_match('#^'.$pattern.'$#', $action, $params);
        if (!$match) return false;
        array_shift($params);
        $this->params = $params;
        return true;
    }
    
    /**
     * Returns a param by index
     * If index is not provided, returns all params
     * 
     * @param integer $index
     * @return boolean
     */
    public function getParam($index = null) {
        if ($index === null) return $this->params;
        if (!isset($this->params[$index])) return false;
        return $this->params[$index];
    }
    
    private function remapFiles($files) {
        $name = key($files);
        if (key($files[$name]) === 'name') {
            $file_count = 1;
            $file_keys = array_keys($files[$name]);
            foreach ($file_keys as $key) {
                $this->files[0][$key] = $files[$name][$key];
            }
        }
        else {
            $file_count = count($files[$name]);
            $file_keys = array_keys($files[$name]);
            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $this->files[$i][$key] = $files[$name][$i][$key];
                }
            }
        }
    }
}
