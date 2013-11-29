<?php

namespace Arch\Rule;

/**
 * IsUrl rule class
 */
class IsUrl extends \Arch\Rule
{
    /**
     * Execute isURL
     * @return \Arch\Rule\IsUrl
     */
    public function execute()
    {
        $this->result = (bool) filter_var($this->params[0], FILTER_VALIDATE_URL);
        return $this;
    }
}