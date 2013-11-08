<?php

namespace Arch;

/**
 * Class event
 */
class Event
{
    
    /**
     * The event name
     * @var string
     */
    protected $name;
    
    /**
     * The event callback
     * @var mixed
     */
    protected $callback;
    
    /**
     * Returns a new event
     * @param string $name The event name
     * @param mixed $callback The event callback
     */
    public function __construct($name, $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }
    
    /**
     * Triggers this event
     * @param mixed $target An optional target
     */
    public function trigger($target = null)
    {
        $fn = $this->callback;
        if (is_callable($fn)) {
            $fn($target);
        }
    }
}
