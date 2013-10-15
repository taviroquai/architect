<?php

namespace Arch\View;

/**
 * Carousel view class
 */
class Carousel extends \Arch\View
{
    
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/carousel.php';
        }
		parent::__construct($tmpl);
        
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/carousel/bootstrap-carousel.js',
            'js'
        );
        
        $this->set('items', array());
	}
    
    /**
     * Add item
     * @param string $text
     * @param string $url
     * @param string $active
     */
    public function addItem($html, $active = 0)
    {
        $this->data['items'][] = (object) array( 
            'html' => $html, 
            'active' => $active
            );
    }
}