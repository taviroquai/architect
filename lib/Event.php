<?php

class Event {

    protected $name;
    protected $callback;
    protected $target;
    
    public function __construct($name, $callback, $object = null) {
        $this->name = $name;
        $this->callback = $callback;
    }
    
    public function trigger($target = null) {
        $target = empty($target) ? $this->target : $target;
        $fn = $this->callback;
        $fn($target);
    }
    
}
