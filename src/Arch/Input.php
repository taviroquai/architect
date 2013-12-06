<?php

namespace Arch;

/**
 * Input class
 */
class Input
{
    protected $default = '/';
    protected $api = 'apache';
    protected $method = 'get';
    protected $httpGet = array();
    protected $httpPost = array();
    protected $httpServer = array();
    protected $params = array();
    protected $files = array();
    protected $raw;
    protected $action;
    
    /**
     * Holds the available rule names
     * @var array
     */
    protected $typesList = array();
    
    /**
     * Holds the validation messages
     * @var array
     */
    protected $messages;
    
    /**
     * Constructor
     * 
     */
    public function __construct($action = '/')
    {
        $this->action = $action;
        $this->method = 'get';
        $this->messages = array();
        
        $items = glob(__DIR__.'/Rule/*.php');
        array_walk($items, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        $this->typesList = $items;
    }
    
    /**
     * Parse global server input
     * @param string $api
     * @param null|array $server
     * @param null|array $get
     * @param null|array $post
     * @param null|array $files
     * @param null|string $raw
     */
    public function parseGlobal(
        $api    = 'server', 
        $server = array(
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/',
            'argv' => array()
        ),
        $get = null,
        $post = null,
        $files = null,
        $raw = ''
    ) {
        $this->api = $api;
        $this->method = isset($server['REQUEST_METHOD']) ?
            strtolower($server['REQUEST_METHOD'])
            : 'get';
        if (!empty($get)) {
            $this->sanitize ($get);
            $this->setHttpGet ($get);
        }
        if (!empty($post)) {
            $this->sanitize ($post);
            $this->setHttpPost ($post);
        }
        if (!empty($files)) {
            $this->setHttpFiles($files);
        }
        if (!empty($raw)) {
            $this->setRawInput($raw);
        }
        if ($server) {
            $this->httpServer = $server;
        }
        if ($this->isCli()) {
            $this->params = $this->httpServer['argv'];
        } else {
            if (!empty($this->httpGet)) {
                $this->params = array_values($this->httpGet);
            } elseif (!empty($this->httpPost)) {
                $this->params = array_values($this->httpPost);
            }
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
        return $this->action;
    }
    
    /**
     * Tries to find user action through all input
     * @param string $base_url The application base url
     * @param string $index_file The application index filename
     */
    public function parseAction($base_url = '/', $index_file = 'index.php')
    {
        // parse action if no action is set
        if (!$this->isCli()) {
            $uri = str_replace(
                array($base_url.'/',$index_file), 
                '', 
                $this->httpServer['REQUEST_URI']
            );
            $end = strpos($uri, '?') === false ? 
                    strlen($uri) : 
                    strpos($uri, '?');
            $uri = '/'.trim(substr($uri, 0, $end), '/');
            if (!empty($uri)) {
                $this->action = $uri;
            }
        }
        else {
            if (!empty($this->params[1])) {
                $this->action = $this->params[1];
            }
        }
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
    
    /**
     * Sanitizes a GET parameter
     * @param string $name The param key to sanitize
     * @param string $filter The type of sanitize filter
     */
    public function sanitizeGet($name, $filter = FILTER_SANITIZE_STRING)
    {
        $this->sanitize($this->httpGet[$name], $filter);
    }
    
    /**
     * Sanitizes a POST parameter
     * @param string $name The param key to sanitize
     * @param string $filter The type of sanitize filter
     */
    public function sanitizePost($name, $filter = FILTER_SANITIZE_STRING)
    {
        $this->sanitize($this->httpPost[$name], $filter);
    }

    /**
     * Does a primary sanitization
     * @param array $mixed
     */
    protected function sanitize(&$mixed, $filter = FILTER_SANITIZE_STRING, $i = 0)
    {
        if ($i > 3) return false;
        if (is_array($mixed)) {
            foreach ($mixed as $k => &$v) {
                $this->sanitize($v, $filter, $i+1);
            }
        } else {
            $mixed = filter_var($mixed, $filter);
        }
    }
    
    /**
     * Runs all the validation rules
     * @return \Arch\Validator
     */
    public function validate($rules)
    {
        $result = true;
        $this->messages = array();
        foreach ($rules as &$rule) {
            $rule->execute();
            $result = $rule->getResult() && $result;
            if (!$rule->getResult()) {
                $message = new \Arch\Message(
                    $rule->getErrorMessage(), 'alert alert-error'
                );
                $this->messages[] = $message;
            }
        }
        return $result;
    }
    
    /**
     * Returns a new validation rule
     * @param string $name The input param
     * @param string $type The type of rule
     * @param string $error_msg The message if invalid input
     * @return \Arch\Rule
     */
    public function createRule($name, $type, $error_msg)
    {
        if (!in_array($type, $this->typesList)) {
            throw new \Exception(
                'Invalid validator rule. Only accept '
                .implode(',', $this->typesList)
            );
        }
        $sanitizeMethod = 'sanitize'.ucfirst($this->method);
        $input = $this->{$this->method}();
        switch ($type) {
            case 'After':
                $rule = new \Arch\Rule\After($name, $input);
                break;
            case 'Before':
                $rule = new \Arch\Rule\Before($name, $input);
                break;
            case 'Between':
                $rule = new \Arch\Rule\Between($name, $input);
                break;
            case 'Depends':
                $rule = new \Arch\Rule\Depends($name, $input);
                break;
            case 'Equals':
                $rule = new \Arch\Rule\Equals($name, $input);
                break;
            case 'IsAlphaExcept':
                $rule = new \Arch\Rule\IsAlphaExcept($name, $input);
                break;
            case 'IsAlphaNumeric':
                $rule = new \Arch\Rule\IsAlphaNumeric($name, $input);
                break;
            case 'IsDate':
                $rule = new \Arch\Rule\IsDate($name, $input);
                break;
            case 'IsEmail':
                $rule = new \Arch\Rule\IsEmail($name, $input);
                $this->$sanitizeMethod($name, FILTER_SANITIZE_EMAIL);
                break;
            case 'IsImage':
                $rule = new \Arch\Rule\IsImage($name, $input);
                break;
            case 'IsInteger':
                $rule = new \Arch\Rule\IsInteger($name, $input);
                $this->$sanitizeMethod($name, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'IsMime':
                $rule = new \Arch\Rule\IsMime($name, $input);
                break;
            case 'IsTime':
                $rule = new \Arch\Rule\IsTime($name, $input);
                break;
            case 'IsUrl':
                $rule = new \Arch\Rule\IsUrl($name, $input);
                $this->$sanitizeMethod($name, FILTER_SANITIZE_URL);
                break;
            case 'Matches':
                $rule = new \Arch\Rule\Matches($name, $input);
                break;
            case 'OneOf':
                $rule = new \Arch\Rule\OneOf($name, $input);
                break;
            case 'Unique':
                $rule = new \Arch\Rule\Unique($name, $input);
                break;
            default:
                $rule = new \Arch\Rule\Required($name, $input);
        }
        $rule->addParam($this->{$this->method}($name));
        $rule->setErrorMessage($error_msg);
        return $rule;
    }
    
    /**
     * Returns the error messages
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
