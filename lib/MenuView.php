<?php

class MenuView extends View {

    public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/demo/main_menu.php';
        parent::__construct($tmpl);
        
        // init items
        $this->data['items'] = array();
    }
    
    public function addItem($text, $url, $class = '') {
        $this->data['items'][] = (object) array(
            'text' => $text, 
            'url' => $url, 
            'cssClass' => $class
        );
    }
}
