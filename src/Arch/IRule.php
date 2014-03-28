<?php

namespace Arch;

/**
 * IRule class
 */
abstract class IRule
{
    /**
     * Holds the rule input name
     * @var string
     */
    protected $name;
    
    /**
     * Holds the default validation message
     * @var string
     */
    protected $msg = 'Invalid input';
    
    /**
     * Holds the additional input params
     * @var array
     */
    protected $params = array();
    
    /**
     * Holds the rule validation result
     * @var boolean
     */
    protected $result = true;
    
    /**
     * Returns a new input validation rule
     * @param string $name The input param
     */
    public function __construct($name)
    {
        if  (!is_string($name) || empty($name)) {
            throw new \Exception('Invalid rule name');
        }
        $this->name = $name;
    }
    
    /**
     * Executes the rule
     */
    public abstract function run();
    
    /**
     * Sets the error message on fail
     * @param string $msg The message
     * @return \Arch\IRule
     */
    public function setErrorMessage($msg)
    {
        if  (!is_string($msg) || empty($msg)) {
            throw new \Exception('Invalid rule error message');
        }
        $this->msg = $msg;
        return $this;
    }
    
    /**
     * Sets the validation actions params
     * Please look at the validation manual
     * @param mixed $params The action params
     * @return \Arch\IRule
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    
    /**
     * Sets the validation result
     * @param bool $result The validation result
     * @return \Arch\IRule
     */
    public function setResult($result)
    {
        $this->result = (boolean) $result;
        return $this;
    }
    
    /**
     * Adds a validation action param
     * Please see the validation manual
     * @param mixed $param The action param
     * @return \Arch\IRule
     */
    public function addParam($param)
    {
        $this->params[] = $param;
        return $this;
    }
    
    /**
     * Returns the rule input param
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Returns the error message
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->msg;
    }

    /**
     * Returns the action params
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Returns the rule validation result
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Check whether the value is an associative array
     * @param array $v
     * @return bool
     */
    public function isAssoc($v)
    {
        return (bool) (array_values($v) !== $v);
    }
    
    /**
     * Resolve dynamic value from param index
     * @param type $index
     * @return \Arch\IRule
     */
    public function resolveDynamicParam($index)
    {
        $list = $this->params[$index];
        if (is_callable($list)) {
            $array = $list();
        }
        else {
            $array = $list;
        }
        if ($this->isAssoc($array)) {
            $array = array_values($array);
        }
        return $array;
    }
}
