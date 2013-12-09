<?php

namespace Arch;

/**
 * Description of IFactory
 *
 * @author mafonso
 */
abstract class IFactory
{
    
    public function create($type)
    {
        $type = (string) $type;
        return $this->fabricate($type);
    }
    
    protected abstract function fabricate($type);
}
