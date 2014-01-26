<?php

namespace Arch\Rule;

/**
 * After rule class
 */
class After extends \Arch\IRule
{
    /**
     * Execute after
     * @return \Arch\Rule\After
     */
    public function run()
    {
        $t1 = strtotime($this->params[0]);
        $t2 = strtotime($this->params[1]);
        $this->result =  $t1 > $t2 ? true : false;
        return $this;
    }
}