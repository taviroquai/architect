<?php

namespace Arch\Rule;

/**
 * IsEmail rule class
 */
class IsEmail extends \Arch\IRule
{
    /**
     * Execute isEmail
     * @return \Arch\Rule\IsEmail
     */
    public function execute()
    {
        $this->result = (bool) filter_var($this->params[0], FILTER_VALIDATE_EMAIL);
        return $this;
    }
}