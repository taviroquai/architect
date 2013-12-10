<?php

namespace Arch\Output;

/**
 * Raw output class
 */
class Raw extends \Arch\IOutput
{
    /**
     * Send the output
     */
    public function send()
    {
        echo $this->content;
    }
}
