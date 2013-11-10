<?php

namespace Arch;

/**
 * Validator class
 */
class Validator
{
    /**
     * Holds the application input
     * @var \Arch\Input
     */
    protected $input;
    
    /**
     * Holds the validation rules
     * @var array
     */
    protected $rules;
    
    /**
     * Holds the validation messages
     * @var array
     */
    protected $messages;
    
    /**
     * Holds the final validation result
     * @var boolean
     */
    protected $result;
    
    /**
     * Returns a new validator
     */
    public function __construct(\Arch\Input $input)
    {
        $this->input = $input;
        $this->rules = array();
        $this->messages = array();
        $this->result = true;
    }
    
    /**
     * Runs all the validation rules
     * @return \Arch\Validator
     */
    public function validate()
    {
        $type = $this->input->server('REQUEST_METHOD');
        foreach ($this->rules as &$rule) {
            $callback = array($rule, $rule->getAction());
            $param_arr = array();
            if (is_callable($this->input->{$type})) {
                $param_arr[] = $this->input->{$type}($rule->getName());
            } else {
                $param_arr[] = false;
            }
            $rule_params = $rule->getParams();
            if (!empty($rule_params)) {
                foreach ($rule_params as &$param) {
                    $param_arr[] = $param;
                }
            }
            $rule_result = call_user_func_array($callback, $param_arr);
            $rule->setResult($rule_result);
            $this->result = $rule->getResult() && $this->result;
            if (!$rule->getResult()) {
                $message = new \Arch\Message(
                    $rule->getErrorMessage(), 'alert alert-error'
                );
                $this->messages[] = $message;
            }
        }
        return $this;
    }
    
    /**
     * Returns a new validation rule
     * @param string $name The input param
     * @return \Arch\Rule
     */
    public function createRule($name)
    {
        return new \Arch\Rule\Action($name, $this->input);
    }
    
    /**
     * Adds a new validation rule
     * @param \Arch\Rule $rule The validation rule
     * @return \Arch\Validator
     */
    public function addRule(\Arch\Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }
    
    /**
     * Returns the final validation result
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
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