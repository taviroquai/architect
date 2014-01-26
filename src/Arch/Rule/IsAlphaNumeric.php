<?php

namespace Arch\Rule;

/**
 * IsAlphaNumeric class
 */
class IsAlphaNumeric extends \Arch\IRule
{    
    /**
     * Execute isAlphaNumeric
     * @return \Arch\Rule\IsAlphaNumeric
     */
    public function run()
    {
        $this->result = ctype_alnum($this->params[0]);
        return $this;
    }    
}