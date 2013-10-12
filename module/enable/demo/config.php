<?php

// adding a js here, will be available in every page
// app()->addContent(BASEURL.'/module/enable/demo/demo.js', 'js');

// add main route
app()->addRoute('/', function() {

	// add view to theme
	$view = new View(BASEPATH.'/theme/demo/default.php');
    app()->addContent($view);
});

// add 404 route
app()->addRoute('/404', function()  {

   	// set content
    app()->addContent('<h1>404</h1>');
});

app()->addRoute('/demo', function() {
    
    // adding a js here will be available only for /demo route
    // app()->addContent(BASEURL.'/module/enable/demo/demo.js', 'js');

	// demo of date picker
	app()->addContent(new DatepickerView());
    
    // demo of file upload
    if ($file = app()->input->file(0)) {
        $result = app()->upload($file, BASEPATH.'/theme/data');
        app()->addContent('<p>File upload result: '.$result.'</p>');
        if ($result) app()->download($result);
    }
    $fileuploadView = new FileuploadView();
    $fileuploadView->set('name', 'upload');
	app()->addContent($fileuploadView);
    
    // demo of pagination
	app()->addContent(new PaginationView());
    
    // demo of texarea editor
	app()->addContent(new TextareaView());

    // demo of download file
    if (app()->input->get('download')) {
        app()->output->setContent('{"download": "demo"}');
        app()->output->setHeaders(array('Content-type: application/json'));
        app()->output->send();
        exit();
    }
    $url = 'http://localhost'.app()->url('/demo?download=1');
    $download = app()->curlGet($url);
    app()->addContent(
        '<div class="well">'.
            '<p>cURL POST Demo<br /><em>app()->curl("url", array("demo" => 1));</em></p>'.
            '<pre>'.$download.'</pre>
        </div>');
    
    // demo of the shopping cart
    $cart = new CartView();
    $item = (object) array('name' => 'Product1', 'price' => 30, 'tax' => 0.21);
    $cart->model->insertItem($item, 1, 2);
    $cart->model->updateQuantity(1, 3);
    $cart->model->updateShippingCost(5);
    if (app()->input->get('del')) $cart->model->updateQuantity(1, 0);
    app()->addContent($cart);

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
    app()->redirect(BASEURL);
});


// add event save post
app()->addEvent('register.form.before.view', function() {

    $post = app()->input->post();
    if (!empty($post) && app()->getCaptcha()) {

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
    if (!empty($post)) {
        
        // login user
        $email      = filter_var($post['email']);
        $password   = app()->encrypt(filter_var($post['password']));
        $model      = new UserModel();
        $model->validateEmail($post);
        $target = $model->find('email = ? and password = ?', array($email, $password));
        
        if (!$target) {
            app()->addMessage('Invalid email email/password', 'alert alert-error');
        }
        else {
            app()->session->login = $target->email;
            app()->redirect(BASEURL);
        }
        
        // trigger after login
        app()->triggerEvent('login.form.after.post', $target);
    }
});
