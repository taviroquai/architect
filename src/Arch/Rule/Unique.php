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
        $list = $this->resolveDynamicParam(1);
        $this->result = !in_array($this->params[0], $list);
        return $this;
    }
}