Architect PHP Framework
=======================

Architect PHP Framework uses a pragmatic and modular Web development approach. 
The idea is to create a small API but with the most common features in web 
development.

Online Demo
-----------

An online demo can be found in <http://marcoafonso.pt/tests/architect/>

Install
-------

1. Copy config/development.dist.xml to config/development.xml and edit
2. Open in browser <http://localhost/architect>

Main features
-------------

* Small API - You should not need to learn another language. All features can be
called in the form **app()->featureName()**. Use IDE type hinting.
* Modular and Events architecture
* Theme slots configuration and Idiom strings are XML files (no programming 
skills required)

Common features
----------------------------------------------------
Most features can be called in 1 line of code, ie. **app()->featureName()**. If
you prefer to use functions style, there are also core functions aliases. Se
below.

* Supports multiple themes
* Supports multiple idiom
* Plugin routes with PHP5 anonymous functions
* Supports events for inter-module actions
* Supports screen messages
* Core application logging
* Generate consistent URLs
* Centralized encryption
* Built-in sending email
* Built-in shopping cart
* Built-in anti-span field
* Built-in GET/POST cURL requests
* Built-in file uploads and download attachments
* Built-in datepicker field
* Built-in pagination
* Built-in breadcrumbs
* Built-in carousel
* Built-in input validation
* Built-in line chart
* Built-in tree view
* Built-in file explorer (list and gallery templates)
* Built-in forum
* Built-in leaflet map
* More to come...

This is an on-going work and there is not yet a stable version

Quick Start
-----------

1. Create a new folder in module/enable/hello
2. Create a new file in module/enable/hello/config.php
3. Add the following content to config.php

        <?php 
            app()->addRoute('/hello', function() {
            $message = 'Hello World!';
            app()->addContent($message);
        });

4. Open <http://localhost/architect/index.php/hello>


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

User registration module example - OOP style
--------------------------------

/module/demo/config.php

Show user form

    app()->addRoute('/register', function() {
        app()->triggerEvent('register.form.before.view');
        app()->addContent(new RegisterView());
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

User registration module example - Function alias style
--------------------------------

/module/demo/config.php

Show user form

    r('/register', function() {
        tr('register.form.before.view');
        c(new RegisterView());
    });
    
Save user on database

    e('register.form.before.view', function() {
        if (p() && app()->getCaptcha()) {
            $model = new UserModel();
            $result = $model->register(p('email'), p());
            if ($result) app()->redirect('/register-success');
        }
    });

API usage examples
------------------

All code has type hinting in ie. NetBeans IDE, so it's easy to start.

### ALIAS

Architect framework is built using OOP but if you prefer to use functions code
styling there are also core function aliases that may speed up development. 
The first letter gives an idea of what it does.
Remember to use IDE type hinting to know how the alias works.

app() - **A**pplication. The main gate to access features  
r() - **R**oute. Adds a new route  
c() - **C**ontent. Adds content to the default theme  
u() - **U**RL. Returns an internal URL. Use this to generate internal URLs  
m() - **M**essage. Adds a message to be shown to the user  
g() - **G**ET. Returns GET parameters  
j() - **J**SON. Sets JSON output  
o() - **O**utput. Sets the application Output, ie. a View or plain text  
p() - **P**OST. Returns POST parameters  
f() - **F**ILES. Returns a FILES entry by index  
q() - **Q**uery table. Returns a Table instance to start querying  
s() - **S**ecure. Returns a secured (encrypted) string  
t() - **T**ranslate. Returns the translation given by key  
e() - **E**vent. Adds a new event  
tr() - **TR**igger. Triggers the event

### CONSTANTS

    BASE_PATH // constant base path
    BASE_URL  // constant base url

### APP
    app() // return the application singleton

### ROUTER
    app()->addRoute('/my/path', function() { ... }); // PHP5 anonymous function

### THEME
    app()->addContent('Any string or View instance', 'optional slot name');

### URL
    app()->url('/demo', array('param1' => 'World'); // creates an URL
 
### INPUT (GET / POST / RAW / FILES / CLI ARGS)
    app()->input->post('optional post param');

### EVENTS
    app()->register('event.name', function() { ... }); // Register listener
    app()->trigger('event.name', $optional);           // call listener

### DATABASE
    app()->db // Gets a PDO instance
    app()->query('tablename')->select()->run(); // runs and returns a PDOStatement
    app()->query('user')->insert(array('username' => 'admin')->run();
    q('user')->s()->w('id = ?', array(1))->run(); // select user where id = 1
    q('user')->i(array('username' => 'admin'))->run(); // insert into user
    q('user')->u(array('username' => 'guest'))->w('id = ?', array(1))->run(); // update
    q('user')->d('id = ?', array(1))->run(); // delete from user where id = 1
    q('user')->s('group.*')->j('usergroup', 'usergroup.id_group = group.id')->run(); // join

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
    $cart = app()->createCart(); // loads or creates a cart from session
    $cart->model->insertItem($item, 1, 2, 0.2);

### HTTP
    app()->httpGet('http://google.com'); // gets the url content
    app()->httpPost('http://google.com', array('param1' => 'value'));

### UPLOAD
    app()->upload($index, '/var/www/architect/theme/data'); // uploads a file

### DOWNLOAD
    app()->download('/var/www/architect/theme/data/image.jpg'); // force file 
download by sending attachment HTTP headers

### VALIDATION
    $v = app()->createValidator();
    $v->addRule($v->createRule('email')->setAction('isEmail'));

### LINE CHART
    app()->createLineChart();

### TREE VIEW
    app()->createTreeView();

### FILE EXPLORER
    app()->createFileExplorer();
    $tmpl = BASE_PATH.'/theme/default/filegallery.php';
    app()->createFileExplorer($tmpl);

### MAP
    app()->createMap();

### FORUM
    app()->createForum();

ROAD MAP (TODO)
===============
