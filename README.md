Architect PHP Framework
=======================

Architect PHP Framework uses a pragmatic and modular Web development approach. 
The idea is to create a small API but with the most common features in web development.

Some features
-------------

* Small API - You should not need to learn another language
* Modular architecture
* Theme and Idiom configuration (no programming skills required)

Common features
----------------------------------------------------
(most features can be called in 1 line of code, ie. **app()->featureName()**)

* Supports multiple themes
* Supports multiple idiom
* Plugin routes with PHP5 anonymous functions
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
3. Add the following content to config.php

    <?php app()->addRoute('/hello', function() {
        $message = 'Hello World!';
        app()->addContent($message);
    });

4. Open http://localhost/architect/index.php/hello


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

    app()->addRoute('/register', function() {
        app()->triggerEvent('register.form.before.view');
        $view = new RegisterView();
        $view->set('registerUrl', app()->url('/register'));
        app()->addContent($view);
    });
    
Save user on database

    app()->addEvent('register.form.before.view', function() {
        $post = app()->input->post();
        if (!empty($post) && app()->getCaptcha()) {
            $model = new UserModel();
            $result = $model->register($post['email'], $post);
            if ($result) app()->redirect('/register-success');
        }
    });
  
API usage examples
------------------

All code has type hinting in ie. NetBeans IDE, so it's easy to start.


### CONSTANTS

BASEPATH // constant base path

BASEURL  // constant base url

### APP
app() // return the application singleton

### ROUTER
app()->addRoute('/my/path', function() { ... }); // PHP5 anonymous function

### THEME
app()->addContent('Any string or View instance', 'optional slot name');

### URL
app()->url('/demo', array('param1' => 'World'); // creates an application URL
 
### INPUT (GET / POST / RAW / FILES / CLI ARGS)
app()->input->post('optional post param');

### EVENTS
app()->register('event.name', function() { ... }); // Register an event listener

app()->trigger('event.name', $optional);           // call event listener, pass an optional target variable

### DATABASE
app()->db // Gets a PDO instance

### MAIL
app()->mail('test@isp.com', 'subject', $view);

### IDIOM
app()->loadIdiom('filename', 'optional module name'); // loads idiom strings

app()->translate('key'); // returns translated key in filename

t('TITLE'); // An small alias to use in views

### SCREEN MESSAGES
app()->addMessage('An error has occurred', 'alert alert-error');

### CAPTCHA
app()->createCaptcha(); // returns an HTML form element with a captcha code

app()->getCaptcha(); // validates input captcha code
 
### ENCRYPTION
app()->encrypt('my password', 'sha256'); // returns an hash of a string

### REDIRECT
app()->redirect('http://www.google.com'); // redirects to an url

### DATEPICKER
app()->createDatepicker(); // returns a HTML date picker view
 
### FILE UPLOAD
app()->createFileupload()); // returns HTML file upload view
 
### PAGINATION
app()->createPagination(); // returns HTML pagination view

### TEXT EDITOR
app()->createTexteditor(); // returns HTML text editor view

### SHOPPING CART (only use session)
$item = (object) array('name' => 'Product1', 'price' => 30);

$cart = app()->createCart(); // loads or creates a Shopping Cart from session

$cart->model->insertItem($item, 1, 2, 0.2);

### HTTP
app()->httpGet('http://google.com'); // gets the url content

app()->httpPost('http://google.com', array('param1' => 'value'));

### UPLOAD
app()->upload($index, '/var/www/architect/theme/data'); // uploads a file

### DOWNLOAD
app()->download('/var/www/architect/theme/data/image.jpg'); // force file download


ROAD MAP (TODO)
===============
