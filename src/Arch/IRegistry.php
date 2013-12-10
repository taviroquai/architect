<?php

namespace Arch;

/**
 * Description of IRegistry
 *
 * @author mafonso
 */
abstract class IRegistry {

    /**
     * @var array
     */
    protected $storage = array();

    /**
     * sets a value
     *
     * @param string $key
     * @param mixed  $value
     * @return \Arch\IRegistry
     */
    public function set($key, $value)
    {
        $this->storage[(string)$key] = $value;
        return $this;
    }

    /**
     * gets a value from the registry
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        $result = null;
        if ($this->exists($key)) {
            $result = $this->storage[(string)$key];
        }
        return $result;
    }
    
    /**
     * Returns whether the key is set or not
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return isset($this->storage[(string)$key]);
    }
    
    /**
     * Removes a value from storage
     * @param string $key
     * @return \Arch\IRegistry
     */
    public function delete($key)
    {
        if ($this->exists($key)) {
            unset($this->storage[(string)$key]);
        }
        return $this;
    }
}
