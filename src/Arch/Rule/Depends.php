<?php

namespace Arch\Rule;

/**
 * Depends rule class
 */
class Depends extends \Arch\Rule
{    
    /**
     * Execute depends
     * @return \Arch\Rule\Depends
     */
    public function execute()
    {
        $r = true;
        foreach ($this->params[1] as $item) {
            if (!empty($this->params[2])) {
                $r = !empty($item) && $r;
            } else {
                $r = !empty($item) && $r;
            }
        }
        $this->result = $r ? !empty($this->params[0]) : true;
        return $this;
    }
}