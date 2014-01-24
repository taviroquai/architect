<?php

namespace Arch;

/**
 * Input class
 */
abstract class IInput
{
    /**
     * The parsed global input API
     * @var string
     */
    protected $api;
    
    /**
     * Holds the user action router
     * @var \Arch\Registry\Router
     */
    protected $router;
    
    /**
     * Holds the HTTP user agent (UA)
     * @var string
     */
    protected $user_agent;
    
    /**
     * Holds the request host
     * @var string
     */
    protected $host;
    
    /**
     * Holds the request URI
     * @var string
     */
    protected $uri;
    
    /**
     * The list of input params
     * @var array
     */
    protected $params = array();
    
    /**
     * The list of input params
     * @var array
     */
    protected $action_params = array();
    
    /**
     * Holds the raw php input
     * @var string
     */
    protected $raw;
    
    /**
     * The resulting parsed input action
     * @var string
     */
    protected $action;
    
    /**
     * Constructor
     * 
     */
    public function __construct($action = '/', $api = 'apache2handler')
    {
        $this->action = $action;
        $this->api = $api;
        $this->raw = file_get_contents("php://input");
        $this->router = new \Arch\Registry\Router();
    }
    
    /**
     * Tries to find user action through all input
     * @param \Arch\Registry\Config $config The application configuration
     */
    public abstract function parseAction(\Arch\Registry\Config $config);
    
    /**
     * Tells whether or not is a CLI input
     * @return boolean
     */
    public abstract function isCli();
    
    /**
     * Returns an uploaded file or false if does not exists
     * @param int $index The uploaded file index
     * @return boolean
     */
    public abstract function getFileByIndex($index);
    
    /**
     * Returns the input agent
     * @return string
     */
    public abstract function getUserAgent();
    
    /**
     * Returns the input host
     */
    public abstract function getHttpHost();
    
    /**
     * Returns the request uri if exists
     */
    public abstract function getRequestUri();
    
    /**
     * Returns the router registry
     * @return \Arch\Registry\Router
     */
    public function getRouter()
    {
        return $this->router;
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
        $result = false;
        if ($index === null) {
            $result = $this->params;
        } elseif (isset($this->params[$index])) {
            $result = $this->params[$index];
        }
        return $result;
    }

    /**
     * Gets user input action
     * If using command line, will return the first parameter
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * Sets the raw php input
     * @param string $raw
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * Returns the raw php input
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
    
    /**
     * Returns the input api
     * @return string
     */
    public function getAPI()
    {
        return $this->api;
    }
    
    /**
     * Sets the user input action
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = (string) $action;
    }

    /**
     * Sets the input params
     * @param array $params The input params
     */
    public function setActionParams($params)
    {
        $this->action_params = $params;
    }
    
    /**
     * Returns a param by index
     * If index is not provided, returns all params
     * 
     * @param integer $index
     * @return boolean|array|string
     */
    public function getActionParam($index = null)
    {
        if ($index === null) {
            return $this->action_params;
        }
        if (!isset($this->action_params[$index])) {
            return false;
        }
        return $this->action_params[$index];
    }
    
    /**
     * Returns true or false if pattern matches action
     * Matches are populated in $this->params
     * 
     * @param string $pattern
     * @return boolean
     */
    public function parseActionParams($pattern)
    {
        $pattern = str_replace(
            array(':any', ':num'), 
            array('[^/]+', '[0-9]+'), 
            $pattern
        );
        $match = preg_match('#^'.$pattern.'$#', $this->getAction(), $params);
        if (!$match) {
            return false;
        }
        array_shift($params);
        $this->setActionParams($params);
        return $params;
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
        $result = true;
        if ($params[1] !== 'arch') {
            $result = false;
        }
        return $result;
    }

    /**
     * Does a primary sanitization
     * @param mixed $key The input key
     * @param int $filter Type of native PHP filter
     */
    public function sanitize($key, $filter = FILTER_SANITIZE_STRING)
    {
        if (isset($this->params[$key])) {
            if (is_array($this->params[$key])) {
                foreach ($this->params[$key] as $k => $v) {
                    $this->params[$key][$k] = $this->sanitizeVar($v, $filter);
                }
                unset($v);
            } else {
                $this->params[$key] = $this->sanitizeVar(
                    $this->params[$key],
                    $filter
                );
            }
        }
    }
    
    /**
     * Sanitizes a variable
     * @param mixed $var The variable to sanitize
     * @param int $filter The filter to use in sanitization
     */
    protected function sanitizeVar($var, $filter = FILTER_SANITIZE_STRING)
    {
        if ($filter == FILTER_SANITIZE_NUMBER_INT) {
            $var = (int) $var;
        } elseif ($filter == FILTER_SANITIZE_NUMBER_FLOAT) {
            $var = (float) $var;
        } else {
            $var = filter_var($var, $filter);
        }
        return $var;
    }
}
