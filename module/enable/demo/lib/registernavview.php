<?php

class RegisterNavView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/register_navlink.php');
        
        // hide if there is a user logged in
        $login = app()->session->login;
        if (!empty($login)) $this->out = ' ';
    }
}
