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
        \Arch\App::Instance()->log('Event created: '.$name);
    }
    
    public function trigger($target = null)
    {
        $target = empty($target) ? $this->target : $target;
        $fn = $this->callback;
        if (is_callable($fn)) {
            \Arch\App::Instance()->log('Event triggered: '.$this->name);
            $fn($target);
        }
        else {
            \Arch\App::Instance()->log(
                'Event callback is not callable: '.$this->name,
                'error'
            );
        }
    }
}
