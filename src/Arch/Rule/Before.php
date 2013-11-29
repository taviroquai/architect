<?php

namespace Arch\Rule;

/**
 * Before rule class
 */
class Before extends \Arch\Rule
{    
    /**
     * Execute before
     * @return \Arch\Rule\Before
     */
    public function execute()
    {
        $t1 = strtotime($this->params[0]);
        $t2 = strtotime($this->params[1]);
        $this->result =  $t1 < $t2 ? true : false;
        return $this;
    }
}