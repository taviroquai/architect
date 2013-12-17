<?php

namespace Arch\Rule;

/**
 * IsInteger rule class
 */
class IsInteger extends \Arch\IRule
{    
    /**
     * Execute isInteger
     * @return \Arch\Rule\IsInteger
     */
    public function execute()
    {
        $this->params[0] = (string) $this->params[0];
        $this->result = (bool) ctype_digit($this->params[0]);
        return $this;
    }
}