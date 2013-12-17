<?php

namespace Arch\Rule;

/**
 * Between rule class
 */
class Between extends \Arch\IRule
{        
    /**
     * Execute between
     * @return \Arch\IRule\Between
     */
    public function execute()
    {
        $t1 = strtotime($this->params[0]);
        $t2 = strtotime($this->params[1]);
        $t3 = strtotime($this->params[2]);
        $r1 = $t1 >= $t2 ? true : false;
        $r2 = $t1 <= $t3 ? true : false;
        $this->result = (bool) ($r1 && $r2);
        return $this;
    }
}