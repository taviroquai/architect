<?php

namespace Arch;

/**
 * Validator class
 */
class Validator
{
    protected $app;
    protected $rules = array();
    protected $result = true;
    
    /**
     * Returns a new validator
     */
    public function __construct(\Arch\App $app)
    {
        $this->app = $app;
    }
    
    /**
     * Runs all the validation rules
     * @return \Arch\Validator
     */
    public function validate()
    {
        $type = $this->app->input->server('REQUEST_METHOD');
        foreach ($this->rules as &$rule) {
            $callback = array($rule, $rule->getAction());
            $param_arr = array();
            $param_arr[] = $this->app->input->{$type}($rule->getName());
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
                $this->app->addMessage (
                    $rule->getErrorMessage(),
                    'alert alert-error'
                );
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
        return new \Arch\Rule\Action($name, $this->app);
    }
    
    /**
     * Adds a new validation rule
     * @param \Arch\Rule $rule
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

}