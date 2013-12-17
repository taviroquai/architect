<?php

namespace Arch\Rule;

/**
 * OneOf rule class
 */
class OneOf extends \Arch\IRule
{
    /**
     * Execute oneOf
     * @return \Arch\Rule\OneOf
     */
    public function execute()
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
        $this->result = in_array($this->params[0], $array);
        return $this;
    }
}