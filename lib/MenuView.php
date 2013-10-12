<?php

class MenuView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/main_menu.php');
        
        // init items
        $this->data['items'] = array();
        
        // add demo menu item
        $this->addItem('Demo', app()->url('/demo'));
    }
    
    public function addItem($text, $url, $class = '') {
        $this->data['items'][] = (object) array('text' => $text, 'url' => $url, 'cssClass' => $class);
    }
}
