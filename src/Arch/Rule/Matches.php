<?php

namespace Arch\Rule;

/**
 * Matches class
 */
class Matches extends \Arch\IRule
{    
    /**
     * Execute matches
     * @return \Arch\Rule\Matches
     */
    public function run()
    {
        $this->result = preg_match($this->params[1], $this->params[0]);
        return $this;
    }
}