<?php

namespace Arch\Output;

/**
 * Raw output class
 */
class Raw extends \Arch\Output
{
    /**
     * Send the output
     */
    public function send()
    {
        echo $this->content;
    }
}
