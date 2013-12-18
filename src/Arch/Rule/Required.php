<?php

namespace Arch\Rule;

/**
 * Required rule class
 */
class Required extends \Arch\IRule
{    
    /**
     * Execute required
     * @return \Arch\Rule\Required
     */
    public function run()
    {
        $this->result = !empty($this->params[0]);
        return $this;
    }
}