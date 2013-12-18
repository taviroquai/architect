<?php

namespace Arch\Rule;

/**
 * Before rule class
 */
class Before extends \Arch\IRule
{    
    /**
     * Execute before
     * @return \Arch\IRule\Before
     */
    public function run()
    {
        $t1 = strtotime($this->params[0]);
        $t2 = strtotime($this->params[1]);
        $this->result =  $t1 < $t2 ? true : false;
        return $this;
    }
}