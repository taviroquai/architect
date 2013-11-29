<?php

namespace Arch\Rule;

/**
 * IsAlphaNumeric except class
 */
class IsAlphaExcept extends \Arch\Rule
{    
    /**
     * Execute isAlphaExcept
     * @return \Arch\Rule\IsAlphaExcept
     */
    public function execute()
    {
        $except = isset($this->params[1]) ? $this->params[1] : '\-_';
        $pattern = "/[a-zA-Z0-1$except]/";
        $this->result = (bool) preg_match($pattern, $this->params[0]);
        return $this;
    }
    
}