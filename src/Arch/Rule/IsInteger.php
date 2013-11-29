<?php

namespace Arch\Rule;

/**
 * IsInteger rule class
 */
class IsInteger extends \Arch\Rule
{    
    /**
     * Execute isInteger
     * @return \Arch\Rule\IsInteger
     */
    public function execute()
    {
        $this->result = (bool) is_int($this->params[0]);
        return $this;
    }
}