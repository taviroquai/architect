<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
  <h1><?=t('TITLE')?></h1>
  
  <p>Architect PHP Framework uses a pragmatic and modular Web development approuch. The idea is to create a small API but with the most common features in web development.</p>
  <p>Some features are</p>
  <ul>
      <li>Small API - You should not need to learn another language</li>
      <li>Modular architecture</li>
      <li>Theme and Idiom configuration (no programming skills required)</li>
      <li>Commom features (most can be done in 1 line of code):
          <ul>
              <li>Supports multiple themes</li>
              <li>Supports multiple idiom</li>
              <li>Plugin in routes callbacks with anonymous functions</li>
              <li>Supports events for inter-module actions</li>
              <li>Supports screen messages</li>
              <li>Generate consistent URLs</li>
              <li>Centralized encryption</li>
              <li>Built-in sending email</li>
              <li>Built-in shooping cart</li>
              <li>Built-in anti-span field</li>
              <li>Built-in GET/POST cURL requests</li>
              <li>Built-in file uploads and download attachments</li>
              <li>Built-in datepicker field</li>
              <li>Built-in pagination</li>
              <li>More to come...</li>
          </ul>
      </li>
  </ul>
  <p>This is on going and there is not a stable version yet</p>
  
  <h2>Quick Start</h2>
  <ul>
      <li>Create a new folder in <strong>module/enable/hello</strong></li>
      <li>Create a new file in <strong>module/enable/hello/config.php</strong></li>
      <li>Add the following content to config.php
          <pre>
&lt;?php

app()->addRoute('/hello', function() {
    $message = '&lt;h1&gt;Hello World!&lt;h1&gt;';
    app()->addContent($message);
});
          </pre>
      </li>
      <li>Open in browser <em><a href="http://localhost/architect/index.php/hello">
                  http://localhost/architect/index.php/hello
              </a>.</em> Enjoy!</li>
  </ul>
  
  <h2>Theme Configuration without programming skills</h2>
  <em>/theme/default/theme.xml</em>
  <pre>
&lt;config&gt;
    &lt;slot name="topbar"&gt;
        &lt;module&gt;
            &lt;classname&gt;LoginNavView&lt;/classname&gt;
        &lt;/module&gt;
    &lt;/slot&gt;
&lt;/config&gt;
  </pre>
  
  <h2>Idiom configuration without programming skills</h2>
  <em>/idiom/en/default.xml</em>
  <pre>
&lt;items&gt;
	&lt;item key="TITLE">Architect PHP Framework&lt;/item&gt;
&lt;/items&gt;
  </pre>
  
  <h2>User registration module example</h2>
  
  <em>/module/demo/config.php</em>
  
  <h3>Show user form</h3>
  <pre>

// add user registration route
app()->addRoute('/register', function() {
    
    // trigger before view
    app()->triggerEvent('register.form.before.view');
    
    // add view to content
    $view = new RegisterView();
    $view->set('registerUrl', app()->url('/register'));
    app()->addContent($view);

});
  </pre>
  
  <h3>Save user on database</h3>
  
  <pre>

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
  </pre>
  

  
  <h2>API usage examples</h2>
  <p>All code has type hinting in ie. NetBeans IDE, so it's easy to start.</p>
    <pre>
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
 * app()->addContent('&lt;h1&gt;Any string or View instance&lt;/h1&gt;', 'optional slot name');
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
 * app()->httpGet('http://google.com'); // gets the url content
 * app()->httpPost('http://google.com', array('param1' => 'value'));
 *
 * UPLOAD USAGE
 * app()->upload($index, '/var/www/architect/theme/data'); // uploads a file
 *
 * DOWNLOAD USAGE
 * app()->download('/var/www/architect/theme/data/image.jpg'); // force file download
 *
 */
  </pre>
  
  <h2>ROAD MAP (TODO)</h2>
</div>