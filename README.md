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

An online demo can be found in <http://marcoafonso.pt/tests/architect/>

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

/theme/default/slots.xml

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

app() - **App**lication. The main gate to access features  
conf() - **Conf**iguration item. Returns a configuration item  
view() - Returns the generic **view** factory  
help() - Returns the **help**er factory  
theme() - Loads a **theme** or returns current theme  
session() - Sets or gets a **session** item  
redirect() - Sends **location HTTP header** and save session before exit  
filter() - calls Input to **sanitize** an input param  
r() - **R**oute. Adds a new route  
c() - **C**ontent. Adds content to the default theme  
u() - **U**RL. Returns an internal URL. Use this to generate internal URLs  
m() - **M**essage. Adds a message to be shown to the user  
i() - Returns all **i**nput parameters or just one from GET/POST  
j() - **J**SON. Sets JSON output  
o() - **O**utput. Sets the application Output, ie. a View or plain text  
f() - **F**ILES. Returns a FILES entry by index  
q() - **Q**uery table. Returns a Table instance to start querying  
s() - **S**ecure. Returns a secured (encrypted) string  
e() - **E**vent. Adds a new event  
tr() - **TR**igger. Triggers the event  
v() - **V**iew. Creates a new view. You can pass a template file path  
l() - Layout. Creates a new layout - a view with layout slots operations   

### APP
    app() // returns the application instance

### ROUTER
    r('/my/path/(:any)', function ($param) { ... }); // Adds a route callback

### THEME
    c('Hello World!'); // Adds content to default theme

### URL
    u('/demo', array('param1' => 'World')); // creates an URL
 
### INPUT (GET / POST / RAW / FILES / CLI ARGS)
    i('username'); // returns $_GET['username'] or $_POST['username']
    f(0); // returns $_FILES['file'] or $_FILES['file'][0] for multiple

### EVENTS
    e('my.event.name', function($target) { ... }); // Register an event
    tr('my.event.name', $target); // Triggers an event and passes a variable

### CORE EVENTS

There are core events that allows to change application workflow without 
changing the core system. These are:

    'arch.module.after.load'
    'arch.database.load'
    'arch.session.load'
    'arch.theme.load'
    'arch.action.before.call'
    'arch.output.before.send'
    'arch.session.save'
    'arch.before.end'

### IDIOM
    $i = help()->createIdiom();  // tries to find a default idiom by session or input
    $i->loadTranslation('filename', 'optional module name'); // loads a translation file
    $i->translate('key', array('key' => 'World')); // returns translated key in filename
    $i->t('TITLE'); // An alias to use in templates

### SCREEN MESSAGES
    m('An error has occured', 'css class'); // Adds a flash message
    app()->flushMessages(); // Returns array. Remember to call flush in template

### CAPTCHA
    $v = view()->createAntiSpam(); // returns an HTML antispam element
    $v->validate(); // validates antispam code (saved in session)
 
### ENCRYPTION
    s('secure this string'); // returns an encrypted string (crypt)

### REDIRECT
    redirect('http://www.google.com'); // redirects to an url

### VALIDATION
    $v = help()->createValidator();
    $rules[] = $v->createRule('email', 'isEmail', 'Invalid email message);
    $result = $v->validate($rules);
    app()->getSession()->loadMessages($v->getMessages());

### HTTP
    $curl = help()->createCurl('http://google.com'); // gets a Curl helper
    $result = $curl->execute(); // Gets the URL response

### FILE UPLOAD
    $v = view()->createFileUpload(); // Creates an upload field
    $v->upload(f($index), '/var/www/architect/theme/data'); // uploads a file

### DOWNLOAD
    $helper = help()->createDownload('/var/www/architect/theme/data/image.jpg');
    $helper->execute(); // forces a file download

### DATABASE
    q('user')->s()->w('id = ?', array(1))->run(); // select user where id = 1
    q('user')->i(array('username' => 'admin'))->run(); // insert into user
    q('user')->u(array('username' => 'guest'))->w('id = ?', array(1))->run(); // update
    q('user')->d('id = ?', array(1))->run(); // delete from user where id = 1
    q('user')->s('group.*')->j('usergroup', 'usergroup.id_group = group.id')->run(); // join

### DATEPICKER
    view()->createDatepicker(); // returns a HTML date picker view
 
### PAGINATION
    view()->createPagination(); // returns HTML pagination view

### TEXT EDITOR
    view()->createTexteditor(); // returns HTML text editor view

### LINE CHART
    view()->createLineChart();

### TREE VIEW
    view()->createTreeView();

### FILE EXPLORER
    $explorer = view()->createFileExplorer();
    $explorer->setPath($path);

### MAP
    view()->createMap();

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
    $v = view()->createAutoTable(); // returns an html table view
    $v->setConfig($config);
    $v->setDatabaseDriver($db);
    $v->setPagination($pagination);

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
    $v = view()->createAutoForm(); // returns an html form view
    $v->setConfig($config);
    $v->setDatabaseDriver($db);

ROAD MAP (TODO)
===============

January 2014 - Version 1.0.0-beta  
February 2014 - Version 1.0.0
