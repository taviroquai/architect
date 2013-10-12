<?php

class RegisterView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/register_form.php');

        // set data
        $this->set('registerUrl', '/register');
        $email = app()->input->post('email');
        if (!empty($email)) $this->set('email', filter_var($email));
    }
}
