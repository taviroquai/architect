<?php

class DemoMenuNavView extends View_Menu {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/main_menu.php');
        
        // add demo menu item
        $this->addItem('Demo', app()->url('/demo'));
    }
}
