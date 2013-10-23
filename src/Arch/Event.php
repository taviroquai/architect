<?php

namespace Arch;

/**
 * Class event
 */
class Event
{

    protected $name;
    protected $callback;
    protected $target;
    
    public function __construct($name, $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }
    
    public function trigger($target = null)
    {
        $target = empty($target) ? $this->target : $target;
        $fn = $this->callback;
        if (is_callable($fn)) {
            $fn($target);
        }
    }
}
