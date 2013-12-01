<?php

namespace Arch\View;

/**
 * Carousel view class
 */
class Carousel extends \Arch\View
{
    /**
     * Returns a new Carousel view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','carousel.php'));
        }
		parent::__construct($tmpl);
        
        $this->set('items', array());
	}
    
    /**
     * Adds an item to the carousel (html)
     * @param string $text The item text
     * @param string $url The item url
     * @param string $active Tells whether is the first slide or not
     */
    public function addItem($html, $active = 0)
    {
        $this->data['items'][] = (object) array( 
            'html' => $html, 
            'active' => $active
            );
    }
}