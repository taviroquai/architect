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
     * Validates type of object to fabricate
     * @param string $type Type of object
     * @throws \Exception
     */
    protected function validateType($type, $pattern)
    {
        $type = (string) $type;
        $available = glob($pattern);
        array_walk($available, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
        if (!in_array($type, $available)) {
            throw new \Exception(
                'Invalid generic view type. '
                .'Use one of the following strings: '.implode(', ', $available)
            );
        }
    }
    
    /**
     * Returns a new factory object
     * @param string|int A type of object
     */
    protected abstract function fabricate($type);
    
}
