<?php

namespace Arch\Rule;

/**
 * Matches class
 */
class Matches extends \Arch\Rule
{    
    /**
     * Execute matches
     * @return \Arch\Rule\Matches
     */
    public function execute()
    {
        $this->result = preg_match($this->params[1], $this->params[0]);
        return $this;
    }
}