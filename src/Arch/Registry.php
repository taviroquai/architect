<?php

namespace Arch;

/**
 * Description of Registry
 *
 * @author mafonso
 */
abstract class Registry {

    /**
     * @var array
     */
    protected $storage = array();

    /**
     * sets a value
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->storage[(string)$key] = $value;
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
        if (self::exists($key)) {
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
     */
    public function delete($key)
    {
        if (self::exists($key)) {
            unset($this->storage[(string)$key]);
        }
    }
}
