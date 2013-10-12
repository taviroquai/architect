<?php

class DemoMenuNavView extends MenuView {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/main_menu.php');
        
        // add demo menu item
        $this->addItem('Demo', app()->url('/demo'));
    }
}
