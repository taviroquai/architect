<?php

namespace Arch\View;

/**
 * TreeView class
 */
class TreeView extends \Arch\View
{

    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','treeview.php'));
        }
        parent::__construct($tmpl);
        
        // init items
        $this->data['tree'] = array();
    }
    
}
