<?php

namespace Arch\Rule;

/**
 * IsUrl rule class
 */
class IsUrl extends \Arch\IRule
{
    /**
     * Execute isURL
     * @return \Arch\Rule\IsUrl
     */
    public function run()
    {
        $this->result = (bool) filter_var($this->params[0], FILTER_VALIDATE_URL);
        return $this;
    }
}