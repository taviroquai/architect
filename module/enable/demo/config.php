<?php

// initialization
\Arch\Demo\ModelUser::checkDatabase();

// add main route
r('/', function() {
	// add content
    c(new \Arch\View(BASE_PATH.'/theme/demo/default.php'));
});

// add 404 route
r('/404', function()  {
   	// set content
    c('<h1>404</h1>');
});

r('/demo', function() {
    // demo of file upload
    if ($file = f(0)) {
        app()->upload($file, BASE_PATH.'/theme/data');
    }    
    // demo of download file
    if (g('dl')) {
        app()->download(BASE_PATH.'/theme/default/img/'.g('dl'));
    }
    // show demo view
    c(new \Arch\Demo\ViewMain());
});

// add routes
r('/register', function() {
    // trigger before view
    tr('register.form.before.view');
    // add view to content
    $view = new \Arch\Demo\ViewRegister();
    $view->set('registerUrl', u('/register'));
    c($view);
});

// add routes
r('/register-success', function() {
    c('<p>Thank you for registering</p>');
});

// add login route
r('/login', function() {
    // trigger before view
    tr('login.form.before.view');
    // add view to content
    $view = new \Arch\Demo\ViewLogin();
    $view->set('loginUrl', app()->url('/login'));
    $view->set('logoutUrl', app()->url('/logout'));
    c($view);
});

// add logout route
r('/logout', function() {
    // destroy current session and redirect
    app()->session->destroy();
    app()->redirect();
});

// add event save post
e('register.form.before.view', function() {

    if (p('register') && app()->getCaptcha()) {

        // load model
        $model = new \Arch\Demo\ModelUser();
        
        // validate post
        if ($model->validateCreate(p())) {
            
            $email = p('email');
            $view = new \Arch\View(BASE_PATH.'/theme/default/mail.php');
            $view->addContent("Thank you $email for registering!");

            $r = app()->mail($email, 'Register', $view);
            if(!$r) {
                m("Registration failed. Try again.", 'alert alert-error');
            }
            else {
                m("An email was sent to your address");
                // finally register
                $user = $model->register(p());
                // trigger after post
                tr('register.form.after.post', $user);
                // redirect to success page
                app()->redirect(u('/register-success'));
            }
        }
        else sleep(2);
    }
});

// add event try to login
e('login.form.before.view', function() {

    $post = p();
    if (!empty($post['login'])) {
        
        // login user
        $email      = filter_var($post['email']);
        $password   = s(filter_var($post['password']));
        $model      = new \Arch\Demo\ModelUser();
        $model->validateEmail($post);
        $user = $model->find('email = ? and password = ?', array($email, $password));

        if (!$user) {
            m('Invalid email/password', 'alert alert-error');
        }
        else {
            // start user session
            app()->session->login = $user->email;
            // trigger after login
            tr('login.form.after.post', $user);
            app()->redirect();
        }
    }
});
