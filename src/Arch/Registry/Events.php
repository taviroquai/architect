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
     * @param mixed $callback
     * @param mixed $target
     * @throws Exception
     * @return \Arch\Event The newly created event
     */
    public function addEvent($name, $callback, $target = null)
    {
        if (!is_string($name) || !is_callable($callback)) {
            throw new \Exception('Invalid event name or event callback');
        }
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
     * @return \Arch\App The application
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
