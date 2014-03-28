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
    public function run()
    {
        $list = $this->resolveDynamicParam(1);
        $this->result = in_array($this->params[0], $list);
        return $this;
    }
}