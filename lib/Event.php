<?php

class Event {

    protected $name;
    protected $callback;
    protected $target;
    
    public function __construct($name, $callback, $object = null) {
        $this->name = $name;
        $this->callback = $callback;
        app()->log('Event created: '.$name);
    }
    
    public function trigger($target = null) {
        $target = empty($target) ? $this->target : $target;
        $fn = $this->callback;
        if (is_callable($fn)) {
            app()->log('Event triggered: '.$this->name);
            $fn($target);
        }
        else app()->log('Event callback is not callable: '.$this->name, 'error');
    }
    
}
