Architect PHP Framework
=======================

Architect PHP Framework uses a pragmatic and modular Web development approach. 
The idea is to create a small API but with the most common features in web 
development.

[![Latest Stable Version](https://poser.pugx.org/taviroquai/architectphp/version.png)](https://packagist.org/packages/taviroquai/architectphp)
[![Build Status](https://travis-ci.org/taviroquai/architect.png?branch=master)](https://travis-ci.org/taviroquai/architect)
[![Total Downloads](https://poser.pugx.org/taviroquai/architectphp/downloads.png)](https://packagist.org/packages/taviroquai/architectphp)

Online Demo
-----------

An online demo can be found in <http://marcoafonso.pt/tests/architect/index.php/>

Install
-------

Look at demo repository at <https://github.com/taviroquai/architect-demo/>

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

* Multiple themes
* Multiple idioms
* Routes with PHP5 anonymous functions
* Events for inter-module actions
* Fluent Interface DB queries - supports MySQL, PostgreSQL and SQLite
* Flash messages
* Application logging
* Input validation and sanitization
* Generic views as shopping cart, anti-span input field, file upload, datepicker,
pagination, breadcrumbs, carousel, line chart, tree view, file explorer, leaflet 
map, comment form, automatic table/form from database tables, image gallery,
poll and text editor
* More to come...


This is an on-going work and there is not yet a stable version

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

API usage examples
------------------

All code has type hinting in ie. NetBeans IDE, so it's easy to start.

### ALIAS

Architect framework is built using OOP but if you prefer to use functions code
styling there are also core function aliases that may speed up development. 
The first letter gives an idea of what it does.
Remember to use IDE type hinting to know how the alias works.

app() - **A**pplication. The main gate to access features  
conf() - **C**onfiguration item. Returns a configuration item  
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
e() - **E**vent. Adds a new event  
tr() - **TR**igger. Triggers the event  
rule() - **RULE**. Creates a new input validation rule  

### APP
    app() // returns the application instance

### ROUTER
    app()->addRoute('/my/path', function() { ... }); // PHP5 anonymous function
    r('/my/path/(:any)', function ($param) { ... });

### THEME
    app()->addContent('Any string or View instance', 'optional slot name');
    c('Hello World!');

### URL
    app()->url('/demo', array('param1' => 'World'); // creates an URL
    u('/demo');
 
### INPUT (GET / POST / RAW / FILES / CLI ARGS)
    app()->input->post('optional post param');
    p('username'); // returns $_POST['username']
    g('param'); // returns $_GET['param']
    f(0); // returns $_FILES['file'] or $_FILES['file'][0] for multiple

### EVENTS
    app()->register('event.name', function() { ... }); // Register listener
    app()->trigger('event.name', $optional);           // call listener
    e('my.event.name', function($target) { ... });
    tr('my.event-name', $target);

### CORE EVENTS

There are core events that allows to change application workflow without 
changing the core system. These are:

    'arch.module.after.load'
    'arch.session.load'
    'arch.theme.after.load'
    'arch.db.after.init'
    'arch.action.before.call'
    'arch.http.before.headers'
    'arch.output.before.send'
    'arch.session.save'
    'arch.before.end'

### IDIOM
    $i = app()->createIdiom();  // tries to find a default idiom by session or input
    $i->loadTranslation('filename', 'optional module name'); // loads a translation file
    $i->translate('key', array('key' => 'World')); // returns translated key in filename
    $i->t('TITLE'); // A smaller alias to use in templates

### SCREEN MESSAGES
    app()->addMessage('An error has occurred', 'alert alert-error');
    m('An error has occured');

### CAPTCHA
    app()->createCaptcha(); // returns an HTML form element with a captcha code
    app()->getCaptcha(); // validates input captcha code
 
### ENCRYPTION
    app()->encrypt('my password', 'sha256'); // returns an hash of a string
    s('secure this string');

### REDIRECT
    app()->redirect('http://www.google.com'); // redirects to an url

### VALIDATION
    $rules[] = rule('email', 'isEmail', 'Invalid email message);
    $result = app()->input->validate($rules);
    app()->session->loadMessages(app()->input->getMessages());

### HTTP
    app()->httpGet('http://google.com'); // gets the url content
    app()->httpPost('http://google.com', array('param1' => 'value'));

### UPLOAD
    app()->upload($index, '/var/www/architect/theme/data'); // uploads a file

### DOWNLOAD
    app()->download('/var/www/architect/theme/data/image.jpg'); // force file 
    download by sending attachment HTTP headers
    app()->download('/var/www/architect/theme/data/image.jpg', false); // do
    not send attachment headers

### DATABASE
    app()->db // Gets a PDO instance
    app()->createQuery('tablename')->select()->run(); // runs and returns a PDOStatement
    app()->createQuery('user')->insert(array('username' => 'admin')->run();
    q('user')->s()->w('id = ?', array(1))->run(); // select user where id = 1
    q('user')->i(array('username' => 'admin'))->run(); // insert into user
    q('user')->u(array('username' => 'guest'))->w('id = ?', array(1))->run(); // update
    q('user')->d('id = ?', array(1))->run(); // delete from user where id = 1
    q('user')->s('group.*')->j('usergroup', 'usergroup.id_group = group.id')->run(); // join

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

### AUTO TABLE
    $config = array(
        'table'     => 'demo_user',
        'select'    => 'demo_user.*',
        'columns'   => array(
            array('type' => 'value', 'label' => 'Email', 'property'  => 'email'),
            array('type' => 'action',   'icon'  => 'icon-edit', 
                'action' => u('/demo/crud/'), 'property' => 'id')
        )
    );
    app()->createAutoTable($config); // returns an html table view from config

### AUTO FORM
    $config = array(
        'table'     => 'demo_user',
        'select'    => 'demo_user.*',
        'action'    => u('/demo/crud/save'),
        'items'     => array(
            array('type' => 'hidden',   'property'  => 'id'),
            array('type' => 'label',    'label' => 'Email'),
            array('type' => 'text',     'property'  => 'email'),
            array('type' => 'breakline'),
            array('type' => 'submit',   'label' => 'Save', 
                'class' => 'btn btn-success inline')
        )
    );
    app()->createAutoForm($config); // returns an html form view from config

ROAD MAP (TODO)
===============
