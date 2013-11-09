<?php

namespace Arch\Rule;

/**
 * Action class
 */
class Action extends \Arch\Rule
{
    
    /**
     * Returns a new input validation rule
     * @param string $name The input param
     */
    public function __construct($name, \Arch\Input $input)
    {
        parent::__construct($name, $input);
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
        $type = $this->input->server('REQUEST_METHOD');
        $confirm_value = $this->input->{$type}($confirm);
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
    
}