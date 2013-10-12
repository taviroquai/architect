<?php

/**
 * Breadcrumbs class
 */
class BreadcrumbsView extends View {
    
	public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/breadcrumbs.php';
		parent::__construct($tmpl);
        
        $this->set('items', array());
	}
    
    /**
     * Parse items from action
     * @param string $action
     */
    public function parseAction($action) {
        $items = explode('/', $action);
        $action = '';
        $i = 0;
        foreach ($items as $item) {
            $text = $i == 0 ? 'Home' : $item;
            $action = ($i == 0) ? '/' : $action.'/'.$item;
            $active = $i == count($items)-1 ? 1 : 0;
            $this->addItem($text, app()->url($action), $active);
            $i++;
        }
    }
    
    /**
     * Add item
     * @param string $text
     * @param string $url
     * @param string $active
     */
    public function addItem($text, $url = '#', $active = 0) {
        $this->data['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'active' => $active
            );
    }
}