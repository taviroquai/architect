<?php

class LoginNavView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/login_navform.php');

        $login = app()->session->login;
        if (empty($login)) {
            $this->set('loginUrl', app()->url('/login'));
        }
        else {
            // set session and logout template
            $this->path = BASEPATH.'/theme/demo/login_navsession.php';

            // set default data
            $this->set('logoutUrl', app()->url('/logout'));
            $model = new UserModel();
            $user = $model->find('email = ?', array($login));
            $this->set('user', $user);
        }
    }
}
