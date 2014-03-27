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
                array(ARCH_PATH,'theme','menu.php'));
        parent::__construct($tmpl);
        
        // init items
        $this->storage['items'] = array();
        $this->setCssClass('nav');
    }
    
    /**
     * Sets the HTML element css class
     * @param string $value The css class name
     */
    public function setCssClass($value)
    {
        $this->set('cssClass', (string) $value);
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
