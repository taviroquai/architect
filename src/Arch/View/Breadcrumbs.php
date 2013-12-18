<?php

namespace Arch\View;

/**
 * Breadcrumbs class
 */
class Breadcrumbs extends \Arch\Registry\View
{
    /**
     * Returns a new breadcrumbs view
     */
    public function __construct()
    {

        $tmpl = implode(DIRECTORY_SEPARATOR, 
                array(ARCH_PATH,'theme','breadcrumbs.php'));
	parent::__construct($tmpl);
        
        $this->set('items', array());
        $this->set('home', 'Home');
    }
    
    /**
     * Parse items from input action
     */
    public function parseAction(\Arch\App $app)
    {
        $items = explode('/', $app->getInput()->getAction());
        $action = '';
        $i = 0;
        foreach ($items as $item) {
            $text = $i == 0 ? $this->get('home') : $item;
            $action = 
                ($i == 0) ? '/' : 
                    ($i == 1) ? '/'.$item : 
                    $action.'/'.$item;
            $active = $i == count($items)-1 ? 1 : 0;
            $this->addItem(
                $text,
                $app->getHelperFactory()->createURL($action)->run(),
                $active
            );
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
        $this->storage['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'active' => $active
            );
    }
}