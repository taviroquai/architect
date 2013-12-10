<?php

namespace Arch\View;

/**
 * View Menu
 */
class Menu extends \Arch\Registry\View
{

    /**
     * Returns a new menu view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','main_menu.php'));
        parent::__construct($tmpl);
        
        // init items
        $this->storage['items'] = array();
    }
    
    /**
     * Adds an item to menu
     * @param string $text The item text
     * @param string $url The item URL
     * @param string $class The class HTML attribute
     */
    public function addItem($text, $url, $class = '')
    {
        $this->storage['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'cssClass' => $class
        );
    }
}
