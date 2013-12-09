<?php

namespace Arch;

/**
 * Input class
 */
abstract class Input
{
    /**
     * The parsed global input API
     * @var string
     */
    protected $api;
    
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
    }
    
    /**
     * Tries to find user action through all input
     * @param string $base_url The application base url
     * @param string $index_file The application index filename
     */
    public abstract function parseAction(
        $base_url = '/',
        $index_file = 'index.php'
    );
    
    /**
     * Tells whether or not is a CLI input
     * @return boolean
     */
    public abstract function isCli();
    
    /**
     * Returns an uploaded file or false if does not exists
     * @param $index The uploaded file index
     * @return boolean
     */
    public abstract function getFileByIndex($index);

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
     * @return boolean
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
     * Returns a new input validator
     * @return \Arch\Validator
     */
    public function createValidator()
    {
        return new \Arch\Validator($this);
    }

    /**
     * Does a primary sanitization
     * @param string
     */
    public function sanitize($key, $filter = FILTER_SANITIZE_STRING)
    {
        if (isset($this->params[$key])) {
            if (is_array($this->params[$key])) {
                foreach ($this->params[$key] as $k => &$v) {
                    $this->params[$key][$k] = filter_var($v, $filter);
                    if ($filter == FILTER_SANITIZE_NUMBER_INT) {
                        $v = (int) $v;
                    }
                }
                unset($v);
            } else {
                $this->params[$key] = filter_var($this->params[$key], $filter);
                if ($filter == FILTER_SANITIZE_NUMBER_INT) {
                    $this->params[$key] = (int) $this->params[$key];
                }
            }
        }
    }
}
