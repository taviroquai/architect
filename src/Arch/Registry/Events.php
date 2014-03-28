<?php

namespace Arch\Registry;

/**
 * Description of Events
 *
 * @author mafonso
 */
class Events extends \Arch\IRegistry {
    
    /**
     * Returns a new Events registry
     */
    public function __construct()
    {
        $this->storage = array();
    }
    
    /**
     * Adds a new application event
     * @param string $name
     * @param \Closure $callback
     * @param mixed $target
     * @throws Exception
     * @return \Arch\Event The newly created event
     */
    public function addEvent($name, \Closure $callback, $target = null)
    {
        $evt = new \Arch\Event($name, $callback, $target);
        $this->storage[$name][] = $evt;
        return $evt;
    }
    
    /**
     * Triggers an event by name.
     * 
     * Passes an option $target object
     * 
     * @param string $eventName The event name
     * @param mixed $target An optional target variable
     * @return \Arch\Registry\Events
     */
    public function triggerEvent($eventName, $target = null)
    {
        if ($this->exists($eventName)) {
            foreach ($this->storage[$eventName] as $evt) {
                $evt->trigger($target);
            }
        }
        return $this;
    }
}
