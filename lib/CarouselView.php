<?php

/**
 * Carousel view class
 */
class CarouselView extends View {
    
	public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/carousel.php';
		parent::__construct($tmpl);
        
        app()->addContent(BASEURL.'theme/default/carousel/bootstrap-carousel.js', 'js');
        
        $this->set('items', array());
	}
    
    /**
     * Add item
     * @param string $text
     * @param string $url
     * @param string $active
     */
    public function addItem($html, $active = 0) {
        $this->data['items'][] = (object) array( 
            'html' => $html, 
            'active' => $active
            );
    }
}