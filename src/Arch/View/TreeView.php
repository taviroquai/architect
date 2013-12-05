<?php

namespace Arch\View;

/**
 * TreeView class
 */
class TreeView extends \Arch\View
{

    /**
     * Holds the tree root
     * @var SimpleXMLElement
     */
    public $tree;
    
    /**
     * Returns a new Tree
     * @param string $tmpl The tree template file; note this special template
     */
    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','treeview.php'));
        }
        parent::__construct($tmpl);
        
        // init items
        $this->tree = new \DOMDocument('1.0', 'UTF-8');
        $root = $this->tree->createElement('node');
        $root->setAttribute('label', 'root');
        $this->tree->appendChild($root);
    }

    /**
     * Returns the root node
     * @return \DOMElement
     */
    public function getRoot()
    {
        return $this->tree->documentElement;
    }
    
    /**
     * Creates a node and adds to tree
     * @param string $attribute The first attribute
     * @param string $value The attribute value
     * @param \DOMElement $parent The node parent
     * @return \DOMElement
     */
    public function createNode($attribute, $value, $parent = null)
    {
        $node = $this->tree->createElement('node');
        $node->setAttribute($attribute, $value);
        if (!is_object($parent) || get_class($parent) !== 'DOMElement') {
            $parent = $this->getRoot();
        }
        $parent->appendChild($node);
        return $node;
    }
    
    /**
     * Returns the view as a string
     * @return string
     */
    public function __toString() {
        $this->set('tree', $this->tree);
        return parent::__toString();
    }
}
