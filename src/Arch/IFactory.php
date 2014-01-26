<?php

namespace Arch;

/**
 * IFactory is an abstract factory
 *
 * @author mafonso
 */
abstract class IFactory
{
    /**
     * Creates a new instance by type
     * 
     * Possible values are \Arch::TYPE or string
     * 
     * @param integer $type The type of object
     * @return mixed
     */
    public function create($type)
    {
        $type = (string) $type;
        return $this->fabricate($type);
    }
    
    /**
     * Returns a new factory object
     * @param string|int A type of object
     */
    protected abstract function fabricate($type);
}
