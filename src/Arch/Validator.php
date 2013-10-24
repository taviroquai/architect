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
            $callback = array($this, $rule->getAction());
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
        return new \Arch\Rule($name);
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

    /**
     * Action oneOf
     * 
     * @param mixed $v
     * @param array $list
     * @return bool
     */
    public function oneOf($v, $list)
    {
        if (is_callable($list)) {
            $array = $list();
        }
        else $array = $list;
        if ($this->isAssoc($list)) {
            $array = array_values($array);
        }
        return in_array($v, $array);
    }
    
    /**
     * Action unique
     * 
     * @param mixed $v
     * @param array $list
     * @return bool
     */
    public function unique($v, $list)
    {
        return !$this->oneOf($v, $list);
    }
    
    /**
     * Action matches
     * 
     * @param mixed $v
     * @param string $pattern
     * @return bool
     */
    public function matches($v, $pattern)
    {
        return preg_match($pattern, $v);
    }

    /**
     * Action equals
     * @param mixed $v
     * @param string $confirm
     * @return bool
     */
    public function equals($v, $confirm)
    {
        $type = $this->app->input->server('REQUEST_METHOD');
        $confirm_value = $this->app->input->{$type}($confirm);
        return (bool) ($v === $confirm_value);
    }

    /**
     * Action after
     * @param string $v
     * @param string $time
     * @return bool
     */
    public function after($v, $time)
    {
        $t1 = strtotime($v);
        $t2 = strtotime($time);
        return $t1 <= $t2 ? false : true;
    }
    
    /**
     * Action before
     * @param string $v
     * @param string $time
     * @return bool
     */
    public function before($v, $time)
    {
        $t1 = strtotime($v);
        $t2 = strtotime($time);
        return $t1 <= $t2 ? true : false;
    }
    
    /**
     * Action onINterval
     * @param string $v
     * @param string $time1
     * @param string $time2
     * @return bool
     */
    public function onInterval($v, $time1, $time2)
    {
        $r1 = $this->afterTime($v, $time1);
        $r2 = $this->beforeTime($v, $time2);
        return $r1 & $r2;
    }
    
    /**
     * Action required
     * @param type $v
     * @return type
     */
    public function required($v)
    {
        return !empty($v);
    }
    
    /**
     * Action depends
     * @param mixed $v
     * @param array $list
     * @param bool $unique
     * @return bool
     */
    public function depends($v, $list, $unique = false)
    {
        $r = true;
        foreach ($list as $item) {
            if ($unique) {
                $r = $this->exists ($item) & $r;
            } else {
                $r = !$this->exists ($item) & $r;
            }
        }
        return $r ? !empty($v) : true;
    }
    
    /**
     * Action isDate
     * @param string $v
     * @param string $format
     * @return bool
     */
    public function isDate($v, $format = 'Y-m-d')
    {
        $date = DateTime::createFromFormat($format, $v);
        return $date === FALSE ? false : true;
    }
    
    /**
     * Action isTime
     * @param string $v
     * @param string $format
     * @return bool
     */
    public function isTime($v, $format = 'H:i:s')
    {
        $date = DateTime::createFromFormat($format, $v);
        return $date === FALSE ? false : true;
    }
    
    /**
     * Action isEmail
     * @param string $v
     * @return bool
     */
    public function isEmail($v)
    {
        return (bool) filter_var($v, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Action isURL
     * @param string $v
     * @return bool
     */
    public function isURL($v)
    {
        return (bool) filter_var($v, FILTER_VALIDATE_URL);
    }
    
    /**
     * Action isImage
     * @param string $v
     * @return bool
     */
    public function isImage($v)
    {
        return (bool) getimagesize($v);
    }
    
    /**
     * Action isInteger
     * @param string $v
     * @return bool
     */
    public function isInteger($v)
    {
        $v = (int) $v;
        return (bool) is_int($v);
    }
    
    /**
     * Action isMime
     * @param string $v
     * @param array $list
     * @return bool
     */
    public function isMime($v, $list)
    {
        $finfo = new finfo(FILEINFO_MIME);
        $type = $finfo->file($v);
        return in_array($type, $list);
    }
    
    /**
     * Action isAlphaNum
     * @param string $v
     * @return bool
     */
    public function isAlphaNum($v)
    {
        return ctype_alnum($v);
    }
    
    /**
     * Action isAlphaExcept
     * @param string $v
     * @param string $except
     * @return bool
     */
    public function isAlphaExcept($v, $except = '\-_')
    {
        $pattern = "/[a-zA-Z0-1$except]/";
        return (bool) $this->matches($v, $pattern);
    }
    
    /**
     * Action isAssoc
     * @param array $v
     * @return bool
     */
    public function isAssoc($v)
    {
        return (bool) (array_values($v) !== $v);
    }
}