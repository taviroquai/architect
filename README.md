
Architect PHP Framework
=======================

Architect PHP Framework uses a pragmatic and modular Web development approuch. 
The idea is to create a small API but with the most common features in web development.

Some features
-------------

* Small API - You should not need to learn another language
* Modular architecture
* Theme and Idiom configuration (no programming skills required)

Commom features (most can be done in 1 line of code)
----------------------------------------------------

* Supports multiple themes
* Supports multiple idiom
* Plugin in routes callbacks with anonymous functions
* Supports events for inter-module actions
* Supports screen messages
* Generate consistent URLs
* Centralized encryption
* Built-in sending email
* Built-in shooping cart
* Built-in anti-span field
* Built-in GET/POST cURL requests
* Built-in file uploads and download attachments
* Built-in datepicker field
* Built-in pagination
* More to come...

This is on going and there is not a stable version yet

Quick Start
-----------

1. Create a new folder in module/enable/hello
2. Create a new file in module/enable/hello/config.php
3. Add the following content to config.php and open http://localhost/architect/index.php/hello

    <?php
    app()->addRoute('/hello', function() {
        $message = 'Hello World!';
        app()->addContent($message);
    });
    ?>

Theme Configuration without programming skills
----------------------------------------------

/theme/default/theme.xml

    <config>
        <slot name="topbar">
            <module>
                <classname>LoginNavView</classname>
            </module>
        </slot>
    </config>
      
Idiom configuration without programming skills
----------------------------------------------

/idiom/en/default.xml

    <items>
    	<item key="TITLE">Architect PHP Framework</item>
    </items>

User registration module example
--------------------------------

/module/demo/config.php

Show user form

    // add user registration route
    app()->addRoute('/register', function() {
        // trigger before view
        app()->triggerEvent('register.form.before.view');
        // add view to content
        $view = new RegisterView();
        $view->set('registerUrl', app()->url('/register'));
        app()->addContent($view);
    });
    
Save user on database

    // add form submit event
    app()->addEvent('register.form.before.view', function() {
        $post = app()->input->post();
        if (!empty($post) && app()->getCaptcha()) {
            // save post
            $model = new UserModel();
            $result = $model->register($post['email'], $post);
            // redirect if succeded
            if ($result) app()->redirect('/register-success');
        }
    });
  
API usage examples
------------------

All code has type hinting in ie. NetBeans IDE, so it's easy to start.

/*
 * CONSTANTS USAGE
 *
 * BASEPATH // returns the application base path
 * BASEURL  // returns the application base url
 * 
 * APP USAGE
 * app() // return the application singleton
 * 
 * ROUTER USAGE
 * app()->addRoute('/my/path', function() { ... }); // PHP5 anonymous function
 *
 * THEME USAGE
 * app()->addContent('<h1>Any string or View instance</h1>', 'optional slot name');
 *
 * URL USAGE
 * app()->url('/demo', array('param1' => 'World'); // creates an application URL
 * 
 * INPUT (GET / POST / RAW) USAGE
 * app()->input->post('optional post param');
 *
 * EVENTS USAGE
 * app()->register('event.name', function() { ... }); // Register an event listener
 * app()->trigger('event.name', $optional);           // call event listener, pass an optional target variable
 * 
 * DATABASE USAGE
 * app()->db // Gets a PDO instance
 *
 * MAIL USAGE
 * app()->mail('test@isp.com', 'subject', $view);
 *
 * IDIOM USAGE
 * app()->loadIdiom('filename', 'optional module name'); // loads idiom strings
 * app()->translate('key'); // returns translated key in filename
 * t('TITLE'); // An small alias to use in views
 *
 * USER MESSAGES USAGE
 * app()->addMessage('An error has occurred', 'alert alert-error');
 * 
 * CAPTCHA USAGE
 * app()->setCaptcha(); // returns an HTML form element with a captcha code
 * app()->getCaptcha(); // validates input captcha code
 * 
 * ENCRYPTION USAGE
 * app()->encrypt('my password', 'sha256'); // returns an hash of a string
 * 
 * REDIRECT USAGE
 * app()->redirect('http://www.google.com'); // redirects to an url
 * 
 * DATEPICKER USAGE
 * app()->addContent(new DatepickerView()); // adds HTML date picker
 * 
 * FILE UPLOAD USAGE
 * app()->addContent(new FileuploadView()); // adds HTML file upload
 * 
 * PAGINATION USAGE
 * app()->addContent(new PaginationView()); // adds HTML pagination
 * 
 * SHOPPING CART USAGE (only use session)
 * $cart = new CartView(); // loads a Shopping Cart from session
 * $item = (object) array('name' => 'Product1', 'price' => 30);
 * $cart->model->insertItem($item, 1, 2, 0.2);
 * app()->theme->addContent($cart); // adds HTML shopping cart
 *
 * CURL USAGE
 * app()->curlGet('http://google.com'); // gets the url content
 * app()->curlPost('http://google.com', array('param1' => 'value'));
 *
 * UPLOAD USAGE
 * app()->upload($index, '/var/www/architect/theme/data'); // uploads a file
 *
 * DOWNLOAD USAGE
 * app()->download('/var/www/architect/theme/data/image.jpg'); // force file download
 *
 */
  
ROAD MAP (TODO)
