<?php

namespace Arch;

/**
 * Rule class
 */
class Rule
{
    
    protected $input;
    protected $msg = 'Invalid input';
    protected $action = 'exists';
    protected $params = array();
    protected $result = true;


    /**
     * Returns a new input validation rule
     * @param string $name The input param
     */
    public function __construct($name = null)
    {
        $this->input = $name;
    }
    
    /**
     * Sets the error message on fail
     * @param string $msg The message
     * @return \Arch\Rule
     */
    public function setErrorMessage($msg)
    {
        $this->msg = $msg;
        return $this;
    }
    
    /**
     * Sets the validator action
     * Please see validations actions manual
     * @param string $action The name of the validation action
     * @return \Arch\Rule
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    /**
     * Sets the validation actions params
     * Please look at the validation manual
     * @param string $params The action params
     * @return \Arch\Rule
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    
    /**
     * Sets the validation result
     * @param bool $result The validation result
     * @return \Arch\Rule
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
    
    /**
     * Adds a validation action param
     * Please see the validation manual
     * @param mixed $param The action param
     * @return \Arch\Rule
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
        return $this->input;
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
     * Returns the validator action
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
}