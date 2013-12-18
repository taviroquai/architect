<?php

namespace Arch\Rule;

/**
 * Unique rule class
 */
class Unique extends \Arch\IRule
{    
    /**
     * Execute unique
     * @return \Arch\Rule\Unique
     */
    public function run()
    {
        $list = $this->params[1];
        if (is_callable($list)) {
            $array = $list();
        }
        else {
            $array = $list;
        }
        if ($this->isAssoc($array)) {
            $array = array_values($array);
        }
        $this->result = !in_array($this->params[0], $array);
        return $this;
    }
}