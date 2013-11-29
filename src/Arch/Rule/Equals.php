<?php

namespace Arch\Rule;

/**
 * Equals rule class
 */
class Equals extends \Arch\Rule
{
    /**
     * Execute equals
     * @return \Arch\Rule\Equals
     */
    public function execute()
    {
        $this->result = (bool) ($this->params[0] === $this->params[1]);
        return $this;
    }
}