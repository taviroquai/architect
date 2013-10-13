<?php

// add main route
r('/', function() {

	// add content
    c(new View(BASEPATH.'/theme/demo/default.php'));
});

// add 404 route
r('/404', function()  {

   	// set content
    c('<h1>404</h1>');
});

r('/demo', function() {
    
    // demo of file upload
    if ($file = f(0)) {
        app()->upload($file, BASEPATH.'/theme/data');
    }
    
    // demo of download file
    if (g('dl')) {
        app()->download(BASEPATH.'/theme/default/img/'.g('dl'));
    }
    
    // show demo view
    c(new DemoView());
});

// add routes
r('/register', function() {
    
    // trigger before view
    tr('register.form.before.view');
    
    // add view to content
    $view = new RegisterView();
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
    $view = new LoginView();
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

        // save post
        $model = new UserModel();
        $target = $model->register(p('email'), p());
        
        if ($target) {

            $view = new View(BASEPATH.'/theme/default/mail.php');
            $view->addContent("Thank you {$target->email} for registering!");

            $r = app()->mail($target->email, 'Register', $view);
            if(!$r) {
                m("Registration has failed. Could not send email. Try again.", 'alert alert-error');
                $model->unregister($post['email']);
            }
            else {
                m("Mail Sent OK");
                app()->redirect(u('/register-success'));
            }
        }
        else sleep(2);
        
        // trigger after post
        tr('register.form.after.post', $target);
    }
});

// add event try to login
e('login.form.before.view', function() {

    $post = p();
    if (!empty($post['login'])) {
        
        // login user
        $email      = filter_var($post['email']);
        $password   = s(filter_var($post['password']));
        $model      = new UserModel();
        $model->validateEmail($post);
        $target = $model->find('email = ? and password = ?', array($email, $password));

        if (!$target) {
            m('Invalid email/password', 'alert alert-error');
        }
        else {
            app()->session->login = $target->email;
            app()->redirect();
        }
        
        // trigger after login
        tr('login.form.after.post', $target);
    }
});
