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
            $tmpl = BASE_PATH.'/theme/default/treeview.php';
        }
        parent::__construct($tmpl);
        
        // init items
        $this->data['tree'] = array();
    }
    
}
