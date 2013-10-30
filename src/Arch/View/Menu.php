<?php

namespace Arch\View;

/**
 * View Menu
 */
class Menu extends \Arch\View
{

    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','main_menu.php'));
        }
        parent::__construct($tmpl);
        
        // init items
        $this->data['items'] = array();
    }
    
    public function addItem($text, $url, $class = '')
    {
        $this->data['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'cssClass' => $class
        );
    }
}
