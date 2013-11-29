<?php

namespace Arch\Rule;

/**
 * Required rule class
 */
class Required extends \Arch\Rule
{    
    /**
     * Execute required
     * @return \Arch\Rule\Required
     */
    public function execute()
    {
        $this->result = !empty($this->params[0]);
        return $this;
    }
}