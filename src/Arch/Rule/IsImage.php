<?php

namespace Arch\Rule;

/**
 * IsImage rule class
 */
class IsImage extends \Arch\IRule
{
    /**
     * Execute isImage
     * @return \Arch\Rule\IsImage
     */
    public function execute()
    {
        $this->result = (bool) getimagesize($this->params[0]);
        return $this;
    }
}