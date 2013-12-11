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
     * An optional target
     * @var mixed
     */
    protected $target;
    
    /**
     * Returns a new event
     * @param string $name The event name
     * @param mixed $callback The event callback
     */
    public function __construct($name, $callback, $target = null)
    {
        if (!is_string($name) || empty($name)) {
            throw new \Exception('Invalid event name');
        }
        $this->name = (string) $name;
        $this->callback = $callback;
        $this->target = $target;
    }
    
    /**
     * Triggers this event
     * @param mixed $target An optional target
     */
    public function trigger($target = null)
    {
        if (empty($target)) {
            $target = $this->target;
        }
        $fn = $this->callback;
        $fn($target);
    }
}
