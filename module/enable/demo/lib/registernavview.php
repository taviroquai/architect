<?php

class RegisterNavView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/register_navlink.php');
        
        // hide if there is a user logged in
        if (app()->session->login) $this->hide();
    }
}
