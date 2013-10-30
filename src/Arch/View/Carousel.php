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
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','carousel.php'));
        }
		parent::__construct($tmpl);
        
        $app = \Arch\App::Instance();
        $app->addContent(
            $app->url('/arch/asset/js/bootstrap-carousel.js'),
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