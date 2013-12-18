<?php

namespace Arch\Rule;

/**
 * Depends rule class
 */
class Depends extends \Arch\IRule
{    
    /**
     * Execute depends
     * @return \Arch\IRule\Depends
     */
    public function run()
    {
        $r = true;
        foreach ($this->params[1] as $item) {
            $r = !empty($item) && $r;
        }
        $this->result = $r ? !empty($this->params[0]) : true;
        return $this;
    }
}