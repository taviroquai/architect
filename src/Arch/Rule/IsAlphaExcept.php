<?php

namespace Arch\Rule;

/**
 * IsAlphaNumeric except class
 */
class IsAlphaExcept extends \Arch\IRule
{    
    /**
     * Execute isAlphaExcept
     * @return \Arch\Rule\IsAlphaExcept
     */
    public function run()
    {
        $except = isset($this->params[1]) ? $this->params[1] : '\-_';
        $pattern = "/[a-zA-Z0-1$except]/";
        $this->result = (bool) preg_match($pattern, $this->params[0]);
        return $this;
    }
    
}