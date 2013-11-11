<?php

namespace Arch\View;

/**
 * Breadcrumbs class
 */
class Breadcrumbs extends \Arch\View
{
    /**
     * Returns a new breadcrumbs view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR, 
                    array(ARCH_PATH,'theme','architect','breadcrumbs.php'));
        }
		parent::__construct($tmpl);
        
        $this->set('items', array());
        $this->set('home', 'Home');
	}
    
    /**
     * Parse items from input action
     * @param string $action The input action (\Arch\Input::getAction())
     */
    public function parseAction($action, \Arch\App $app)
    {
        $items = explode('/', $action);
        $action = '';
        $i = 0;
        foreach ($items as $item) {
            $text = $i == 0 ? $this->data['home'] : $item;
            $action = 
                ($i == 0) ? '/' : 
                    ($i == 1) ? '/'.$item : 
                    $action.'/'.$item;
            $active = $i == count($items)-1 ? 1 : 0;
            $this->addItem($text, $app->url($action), $active);
            $i++;
        }
        return $this;
    }
    
    /**
     * Add item
     * @param string $text
     * @param string $url
     * @param string $active
     */
    public function addItem($text, $url = '#', $active = 0)
    {
        $this->data['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'active' => $active
            );
    }
}