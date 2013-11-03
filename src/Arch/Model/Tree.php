<?php

namespace Arch\Model;

/**
 * Tree class
 *
 * @author mafonso
 */
class Tree
{
    
    protected $root;
    
    public function __construct($root = null)
    {
        if (empty($root)) {
            $root = $this->importArray(array('label' => '', 'href' => '#'));
        }
        $this->root = $root;
    }
    
    public function getRoot()
    {
        return $this->root;
    }
    
    public function importArray($array)
    {
        $array['_nodes'] = (object) array();
        return (object) $array;
    }
    
    public function addChild(&$parent, $child)
    {
        $parent->_nodes[] = $child;
        return $parent;
    }
}
