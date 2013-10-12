<?php

// add main route
app()->addRoute('/', function() {

	// add content
    app()->addContent(new View(BASEPATH.'/theme/demo/default.php'));
});

// add 404 route
app()->addRoute('/404', function()  {

   	// set content
    app()->addContent('<h1>404</h1>');
});

app()->addRoute('/demo', function() {
    
    app()->addContent(new DemoView());
});

// add routes
app()->addRoute('/register', function() {
    
    // trigger before view
    app()->triggerEvent('register.form.before.view');
    
    // add view to content
    $view = new RegisterView();
    $view->set('registerUrl', app()->url('/register'));
    app()->addContent($view);
});

// add login route
app()->addRoute('/login', function() {
    
    // trigger before view
    app()->triggerEvent('login.form.before.view');
    
    // add view to content
    $view = new LoginView();
    $view->set('loginUrl', app()->url('/login'));
    $view->set('logoutUrl', app()->url('/logout'));
    app()->addContent($view);
});

// add logout route
app()->router->addRoute('/logout', function() {
    
    // destroy current session and redirect
    app()->session->destroy();
    app()->redirect();
});


// add event save post
app()->addEvent('register.form.before.view', function() {

    $post = app()->input->post();
    if (!empty($post['register']) && app()->getCaptcha()) {

        // save post
        $model = new UserModel();
        $target = $model->register($post['email'], $post);
        
        if ($target) {

            $view = new View(BASEPATH.'/theme/default/mail.php');
            $view->addContent("Thank you {$target->email} for registering!");

            $r = app()->mail($target->email, 'Register', $view);
            if(!$r) {
                app()->addMessage("Registration has failed. Could not send email. Try again.", 'alert alert-error');
                $model->unregister($post['email']);
            }
            else app()->addMessage("Mail Sent OK");
        }
        else sleep(2);
        
        // trigger after post
        app()->triggerEvent('register.form.after.post', $target);
    }
});

// add event try to login
app()->addEvent('login.form.before.view', function() {

    $post = app()->input->post();
    if (!empty($post['login'])) {
        
        // login user
        $email      = filter_var($post['email']);
        $password   = app()->encrypt(filter_var($post['password']));
        $model      = new UserModel();
        $model->validateEmail($post);
        $target = $model->find('email = ? and password = ?', array($email, $password));
        
        if (!$target) {
            app()->addMessage('Invalid email/password', 'alert alert-error');
        }
        else {
            app()->session->login = $target->email;
            app()->redirect();
        }
        
        // trigger after login
        app()->triggerEvent('login.form.after.post', $target);
    }
});
