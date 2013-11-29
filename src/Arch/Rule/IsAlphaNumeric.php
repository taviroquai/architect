<?php

namespace Arch\Rule;

/**
 * IsAlphaNumeric class
 */
class IsAlphaNumeric extends \Arch\Rule
{    
    /**
     * Execute isAlphaNumeric
     * @return \Arch\Rule\IsAlphaNumeric
     */
    public function execute()
    {
        $this->result = ctype_alnum($this->params[0]);
        return $this;
    }    
}