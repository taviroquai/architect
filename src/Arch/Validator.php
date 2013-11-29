<?php

namespace Arch;

/**
 * Validator class
 */
class Validator
{
    /**
     * Holds the available rule names
     * @var array
     */
    protected $typesList = array();
    
    /**
     * Holds the user input
     * @var array
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
    public function __construct($input = array())
    {
        $this->input = $input;
        $this->rules = array();
        $this->messages = array();
        $this->result = true;
        
        $items = glob(__DIR__.'/Rule/*.php');
        array_walk($items, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        $this->typesList = $items;
    }
    
    /**
     * Runs all the validation rules
     * @return \Arch\Validator
     */
    public function validate()
    {
        foreach ($this->rules as &$rule) {
            $rule->execute();
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
     * @param string $type The type of rule
     * @return \Arch\Rule
     */
    public function createRule($name, $type = 'Required')
    {
        if (!in_array($type, $this->typesList)) {
            throw new \Exception(
                'Invalid validator rule. Only accept '
                .implode(',', $this->typesList)
            );
        }
        switch ($type) {
            case 'After':
                $rule = new \Arch\Rule\After($name, $this->input);
                break;
            case 'Before':
                $rule = new \Arch\Rule\Before($name, $this->input);
                break;
            case 'Between':
                $rule = new \Arch\Rule\Between($name, $this->input);
                break;
            case 'Depends':
                $rule = new \Arch\Rule\Depends($name, $this->input);
                break;
            case 'Equals':
                $rule = new \Arch\Rule\Equals($name, $this->input);
                break;
            case 'IsAlphaExcept':
                $rule = new \Arch\Rule\IsAlphaExcept($name, $this->input);
                break;
            case 'IsAlphaNumeric':
                $rule = new \Arch\Rule\IsAlphaNumeric($name, $this->input);
                break;
            case 'IsDate':
                $rule = new \Arch\Rule\IsDate($name, $this->input);
                break;
            case 'IsEmail':
                $rule = new \Arch\Rule\IsEmail($name, $this->input);
                break;
            case 'IsImage':
                $rule = new \Arch\Rule\IsImage($name, $this->input);
                break;
            case 'IsInteger':
                $rule = new \Arch\Rule\IsInteger($name, $this->input);
                break;
            case 'IsMime':
                $rule = new \Arch\Rule\IsMime($name, $this->input);
                break;
            case 'IsTime':
                $rule = new \Arch\Rule\IsTime($name, $this->input);
                break;
            case 'IsUrl':
                $rule = new \Arch\Rule\IsUrl($name, $this->input);
                break;
            case 'Matches':
                $rule = new \Arch\Rule\Matches($name, $this->input);
                break;
            case 'OneOf':
                $rule = new \Arch\Rule\OneOf($name, $this->input);
                break;
            case 'Unique':
                $rule = new \Arch\Rule\Unique($name, $this->input);
                break;
            default:
                $rule = new \Arch\Rule\Required($name, $this->input);
        }
        if (isset($this->input[$name])) {
            $rule->addParam($this->input[$name]);
        } else {
            $rule->addParam(false);
        }
        return $rule;
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
    
    /**
     * Returns the number of rules
     * @return integer
     */
    public function countRules()
    {
        return count($this->rules);
    }

}