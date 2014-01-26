<?php

namespace Arch\Rule;

/**
 * Equals rule class
 */
class Equals extends \Arch\IRule
{
    /**
     * Execute equals
     * @return \Arch\Rule\Equals
     */
    public function run()
    {
        $this->result = (bool) ($this->params[0] === $this->params[1]);
        return $this;
    }
}