<?php

/**
 * App alias. The other aliases are:
 * 
 * r() Adds a new route
 * 
 * c() Adds content to the default theme
 * 
 * u() Returns an internal URL - USE THIS!
 * 
 * g() Returns all $_GET parameters or one parameter value
 * 
 * m() Adds a message to be shown on output theme
 * 
 * p() Returns all $_POST parameters or one parameter value
 * 
 * f() Returns a $_FILES entry by index
 * 
 * s() Returns a secured (encrypted) string - use for passwords
 * 
 * t() Returns the translation given by key
 * 
 * e() Adds a new event
 * 
 * tr() Triggers the event
 * 
 * @return App
 */
function app() { 
    return App::Instance();
}

/**
 * Add route alias
 * @param string $uri The uri query passed in the browser
 * @param function $action The callback to be run
 */
function r($uri, $action) {
    App::Instance()->addRoute($uri, $action);
}

/**
 * Alias of addContent
 * @param string|View $content The content to be rendered
 * @param string $slotName The name of the theme's slot
 * @param boolean $unique Tells if must be the only content
 * @return View The default theme
 */
function c($content, $slotName = 'content', $unique = false) {
    return App::Instance()->theme->addContent($content, $slotName, $unique);
}

/**
 * Add event alias
 * @param string $eventName The event name
 * @param function $callback The callback to be run
 * @param mixed $target An optional object to be passed to the callback
 * @return App The application singleton
 */
function e($eventName, $callback, $target = null) {
    return App::Instance()->addEvent($eventName, $callback, $target);
}

/**
 * Input $_FILES alias
 * @param integer $index The index of the file, default to 0
 * @return array Returns the $_FILES entry
 */
function f($index) {
    return App::Instance()->input->file($index);
}

/**
 * $_GET alias
 * @param string $param The name of the $_GET param
 * @return boolean|mixed|array Returns all params or the param value or false
 */
function g($param = null) {
    return App::Instance()->input->get($param);
}

/**
 * Add message alias
 * @param string $text The message to be shown
 * @param string $cssClass The css class to style
 */
function m($text, $cssClass = 'alert alert-success') {
    App::Instance()->addMessage($text, $cssClass);
}

/**
 * $_POST alias
 * @param string $param The name of the $_POST param
 * @return boolean|mixed|array Returns all params or the param value or false
 */
function p($param = null) {
    return App::Instance()->input->post($param);
}

/**
 * Database table query alias
 * @param string $tableName
 * @param \PDO $db
 * @return \Table
 */
function q($tableName, \PDO $db = null) {
    return App::Instance()->query($tableName, $db);
}

/**
 * Encrypt string alias
 * @param string $string The string to be encrypted
 * @param string $algo The algorithmn to be used for encryption
 * @param string $salt An optional salt
 * @return string The result encrypted string
 */
function s($string, $algo = 'sha256', $salt = '!Zz$9y#8x%7!') {
    return App::Instance()->encrypt($string, $algo, $salt);
}

/**
 * Trigger event alias
 * @param string $eventName The event name
 * @param mixed $target An optional object to be passed
 * @return App The application singleton
 */
function tr($eventName, $target = null) {
    return App::Instance()->triggerEvent($eventName, $target);
}

/**
 * Build internal URL alias
 * @param string $path The internal route key
 * @param array $params Optional QUERY params
 * @return string The final URL
 */
function u($path, $params = array()) {
    return App::Instance()->url($path, $params);
}

/**
 * Translate alias
 * @param string key Idiom key
 * @param array $data Data to be included in idiom string
 * @return string Complete idiom string
 */
function t($key, $data = array()) { 
    return App::Instance()->translate($key, $data);
}

/**
 * Application API
 * 
 * Use alias app() id recomended
 * 
 */
class App implements Messenger {

    /**
     * Holds user input
     * 
     * To return a GET parameter use: app()->input->get('param');
     * To return all GET parameters use: app()->input->get();
     * To return a POST parameter use: app()->input->post('param');
     * To return a FILES entry use: app()->input->file($index);
     * To return raw input use: app()->input->raw();
     * 
     * @var Input
     */
    public  $input;
    
    /**
     * Holds application output.
     * This can be a string, a View or a php file (template).
     * 
     * To add HTML content use: app()->addContent('<p>Hello World</p>');
     * To add a View use: app()->setContent(new View('/path/to/template.php');
     * To output a string use: app()->sendOutput('Hello World!');
     * To output a View use: app()->sendOutput(new View('/path/to/template.php'));
     * @var Output
     */
    public  $output;
    
    /**
     * Default Router
     * 
     * To add a route use: app()->addRoute('/demo', function() { ... });
     * Then the function callback will be called when the user requests
     * index.php/demo
     * 
     * @var Router
     */
    public  $router;
    
    /**
     * Default theme
     * 
     * This will hold the theme (View) that will be used when outputing HTML
     * You can change the theme with: app()->loadTheme('mytheme');
     * This will load the theme configuration into the application
     * 
     * @var View
     */
    public  $theme;
    
    /**
     * Holds user session
     * @var Session
     */
    public  $session;
    
    /**
     * Holds PDO database instance
     * @var PDO
     */
    public  $db;
    
    /**
     * Holds parsed user action
     * @var string
     */
    private  $action;
    
    /**
     * Holds loaded modules
     * @var array
     */
    private  $modules = array();
    
    /**
     * Holds global application events
     * @var array
     */
    private  $events = array();
    
    /**
     * Holds the log file handler
     * @var resource
     */
    private  $log;
    
    /**
     * Application singleton instance
     * @var App
     */
    private static $inst = null;

    /**
     * Gets application instance
     * @return App
     */
    public static function Instance($configPath = null)
    {
        if (self::$inst === null) {
            self::$inst = new App($configPath);
        }
        return self::$inst;
    }

    /**
     * Initialize application
     */
    private function __construct($configPath = null)
    {
        // logging
        $this->log('Open configuration '.$configPath, 'access', true);
        
        // load configuration
        if (!file_exists($configPath)) {
            debug_print_backtrace();
            die("The file $configPath was not found.");
        }
        $xml = @simplexml_load_file($configPath);
        if (!$xml) die('Invalid config.xml file');
        foreach ($xml->item as $item) {
            $name = (string) $item['name'];
            define($name, (string) $item);
        }
        
        // set session handler
        $this->session = new Session();

        // set default output
        $this->theme = new View();
        $this->theme->addSlot('css')->addSlot('js');

        // set default Output
        $this->output = new Output($this->theme);
        
        // set default routes
        $this->router = new Router();
        $this->router->addRoute('/404', function()  {
            app()->output->setHeaders(
                array('HTTP/1.0 400 Not Found', 'Status: 404 Not Found')
                );
            app()->addContent('<h1>There is no content for this route</h1>');
        });
    }
    
    public function run() {
        
        // load session
        $this->session->load();
        
        // initialize default database
        $this->initDatabase();

        // load user input
        $this->loadInput();

        // load enabled modules
        $this->loadModules();

        // load theme class and configuration
        $this->loadTheme(THEME);

        // execute action
        $this->execute();

        // send output
        $this->sendOutput();

        // save session
        $this->session->save();
        
        // close log handler
        if (is_resource($this->log)) fclose ($this->log);
    }
    
    private function execute() {
        $action = $this->router->getRoute($this->action);
        if ($action === false) $action = $this->router->getRoute('/404');
        return call_user_func_array($action, $this->input->getParam());
    }
    
    private function initDatabase() {
        try {
            $this->db = new PDO(DBDSN.';charset=UTF8', DBUSER, DBPASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->log('Database initialized');
        }
        catch (PDOException $e)  {
            $this->addMessage('Check database configuration: '.$e->getMessage(),
                    'alert alert-error');
            $this->log('Database could not be initialized', 'error');
        }
    }
    
    private function loadInput() {
        $this->input = new Input();
        $this->action = $this->input->getAction();
        $this->idiom = new Idiom();
        $this->loadIdiom('default.xml');
    }
    
    private function loadModules() {
        $mods = glob(BASEPATH.'/module/enable/*');
        foreach($mods as $name) {
            if (is_dir($name)) {
                if (is_dir($name.'/lib')) {
                    $includes = glob($name.'/lib/*.php');
                    foreach($includes as $inc) require_once $inc;
                }
                $filename = $name.'/config.php';
                if (file_exists($filename)) {
                    require_once $filename;
                    app()->log('Module loaded: '.$name);
                }
            }
        }
        $this->modules = $mods;
    }
    
    public function log($msg, $label = 'access', $nlb = false) {
        if (!is_resource($this->log)) {
            $filename = BASEPATH.DS.'log'.DS.'log.txt';
            if (!is_writable($filename)) {
                return false;
            }
            $this->log = @fopen($filename, 'a');
            if ($this->log === false) {
                return false;
            }
        }
        $secs = round(microtime(true)-floor(microtime(true)), 3);
        $time = date('Y-m-d H:i:s').' '.sprintf('%0.3f', $secs).'ms';
        $msg = strtoupper($label).' '.$time.' '.$msg.PHP_EOL;
        if ($nlb) $msg = PHP_EOL.$msg;
        if (is_resource($this->log)) fwrite($this->log, $msg);
    }
    
    /**
     * Loads the theme configuration
     * 
     * For example:
     * app()->loadTheme('mytheme');
     * 
     * This will load /theme/mytheme/config.php and /theme/mytheme/theme.xml
     * 
     * Theme slots can be configured with theme.xml without programming
     * Remember that modules that are not enable will not be displayed
     * 
     * You can also add content with, for example:
     * app()->theme->addContent('Hello World');
     * 
     * @param type $name
     * @return \App
     */
    public function loadTheme($name) {
        $theme_path = BASEPATH.'/theme/'.$name;

        $filename = $theme_path.'/config.php';
        if (file_exists($filename)) require_once $filename;
        
        $filename = $theme_path.'/theme.xml';
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->slot as $slot) {
                $slotName = (string) $slot['name'];
                foreach ($slot->module as $item) {
                    $classname = (string) $item->classname;
                    if (!class_exists($classname)) continue;
                    $content = '';
                    if (isset($item->content)) $content = (string) $item->content;
                    $module = new $classname($content);
                    $this->theme->addContent($module, $slotName);
                }
            }
            $this->log('Theme loaded: '.$name);
        }
        return $this;
    }
    
    /**
     * Sends application output
     * 
     * This is a fast way to send text output
     * It will use an Output instance to send
     * 
     * Example:
     * app()->sendOutput('Hello World!');
     * 
     * @param mixed $content
     * @param boolean $now
     */
    public function sendOutput($content = null, $now = true) {
        if (!empty($content)) $this->output->setContent($content);
        else $this->output->setContent($this->theme);
        if ($now) $this->output->send();
    }
    
    /**
     * Sends a redirect HTTP header
     * 
     * This will send an HTTP location header and exit application
     * if now is true
     * 
     * Example:
     * app()->redirect(app()->url('/demo'));
     * 
     * @param string $url
     * @param boolean $now
     */
    public function redirect($url = null, $now = true) {
        if (empty($url)) $url = app()->url('/');
        $output = new Output();
        $output->setHeaders(array('Location: '.$url));
        $output->send();
        $this->log('Redirecting to '.$url);
        if ($now) {
            $this->session->save();
            exit();
        }
    }
    
    /**
     * Allows to add a route and a callback
     * 
     * This is the way to add a route.
     * When the user calls in the browser index.php/demo, the application will
     * look for a route with key /demo and call the action callback
     * 
     * You should add a default route.
     * 
     * Example:
     * app()->addRoute('/', function() { app()->sendOutput('Home'); });
     * 
     * @param string $key
     * @param function $action
     * @return \App
     */
    public function addRoute($key, $action) {
        $result = $this->router->addRoute($key, $action);
        if ($result) $this->log('Add route succeed: '.$key);
        else $this->log('Add route failed: '.$key);
        return $this;
    }

    /**
     * Adds a message to session that can be shown in a view
     * 
     * Messages are very important. A message can be added to the theme in any 
     * part of the application.
     * 
     * Example:
     * app()->addMessage('Correct answer!');
     * 
     * @param string $text
     * @param string $cssClass
     * @return \App
     */
    public function addMessage($text, $cssClass = 'alert alert-success') {
        $this->session->addMessage($text, $cssClass);
        return $this;
    }

    /**
     * Returns session messages
     * 
     * @return array
     */
    public function getMessages() {
        return $this->session->getMessages();
    }
    
    /**
     * Uses a template to display the messages
     * 
     * This is commonly used in themes to display and clear the messages
     * 
     * Example:
     * app()->showMessages(function($item) {
     *     echo '<div>'.$item.'</div>';
     * });
     * 
     * @param function $template
     * @param boolean $flush
     * @return \App
     */
    public function showMessages($template, $flush = true) {

        if (count($this->session->getMessages()) == 0) return;
        $messages = $this->session->getMessages();
        foreach ($messages as $item) {
            $template($item);
        }
        if ($flush) $this->session->clearMessages();
        return $this;
    }

    /**
     * Flushes session messages
     * @return \App
     */
    public function clearMessages() {
        $this->session->clearMessages();
        return $this;
    }
    
    /**
     * Adds an event and a callback
     * 
     * This is very usefull to interact with other modules by not having to
     * hack modules code
     * 
     * Example:
     * app()->addEvent('demo.form.after.post', function($target = null) {
     *     // do something with target
     * });
     * 
     * And then, this will be called by:
     * app()->triggerEvent('demo.form.after.post', $target); // optional target
     * 
     * @param string $eventName
     * @param function $callback
     * @param mixed $target
     * @return \App
     */
    public function addEvent($eventName, $callback, $target = null) {
        if ($target === null) $target = $this;
        $this->events[$eventName][] = new Event($eventName, $callback, $target);
        return $this;
    }
    
    /**
     * Triggers an event by name
     * Passes an option $target object
     * 
     * @param string $eventName
     * @param mixed $target
     * @return \App
     */
    public function triggerEvent($eventName, $target = null) {
        if (!isset($this->events[$eventName])) return;
        foreach ($this->events[$eventName] as $evt) {
            $evt->trigger($target);
        }
        return $this;
    }
    
    /**
     * Adds content to the default theme
     * 
     * This is the common way to show output to the user. USE IT!
     * 
     * Examples:
     * app()->addContent('Hello World');
     * app()->addContent('/path/to/template.php');
     * app()->addContent(new View('/path/to/template.php');
     * 
     * @param mixed $content
     * @param string $slotName
     * @param boolean $unique
     * @return \App
     */
    public function addContent($content, $slotName = 'content', $unique = false) {
        $this->theme->addContent($content, $slotName, $unique);
        return $this;
    }

    /**
     * Builds and returns an internal url
     * 
     * Example:
     * $url = app()->url('/list', array('page' => 1));
     * 
     * @param string $path
     * @param array $params
     * @return string
     */
    public function url($path = '', $params = array()) {
        $base = INDEXFILE == '' ? rtrim(BASEURL, '/') : BASEURL;
        $uri = empty($path) ? '' : $path;
        $query = empty($params) ? '' : '?';
        //foreach ($params as $key => $item) $query .= '&'.rawurlencode($key).'='.rawurlencode($item);
        $query .= http_build_query($params);
        return $base.INDEXFILE.$uri.$query;
    }
    
    /**
     * Returns an hash of a string
     * 
     * Instead of using diferent encryptions spread in the application,
     * use this centralized method.
     * 
     * Example:
     * $password = app()->encrypt('password');
     * 
     * @param string $string
     * @param string $algo
     * @param string $salt
     * @return string
     */
    public function encrypt($string, $algo = 'sha256', $salt = '!Zz$9y#8x%7!') {
        if (in_array($algo, hash_algos())) return hash($algo, $string);
        return (string) md5($string);
    }

    /**
     * Sends an email
     * 
     * Don't worry to configure an email request, but use this method.
     * This uses the phpmailer library.
     * 
     * Example:
     * $result = app()->mail(
     *      'admin@isp.com', 
     *      'Hello', 
     *      new View('/path/to/template.php', array('greet' => 'Hi'))
     * );
     * 
     * @param string $to
     * @param string $subject
     * @param View $view
     * @return boolean
     */
    public function mail($to, $subject, $view) {

        require_once 'vendor/phpmailer/class.phpmailer.php';
        
        try {
            $mail = new PHPMailer(true); // defaults to using php "mail()"
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom(MAILFROM, MAILFROMNAME);
            $mail->AddReplyTo(MAILREPLY, MAILREPLYNAME);
            $mail->AddAddress($to);
            $mail->Subject = $subject;
            $mail->AltBody = "Please use an HTML email viewer!";
            $mail->MsgHTML((string) $view);
            $result = $mail->Send();
            $this->log("Sending mail to $to has succeed");
        }
        catch (phpmailerException $e) {
            $this->log("Sending mail to $to failed: ".$e->errorMessage(), 'error');
            $result = false;
        }
        return $result;
    }

    /**
     * Loads an idiom file
     * 
     * Idiom files are plain xml (no programming skills needed)
     * 
     * Example:
     * app()->loadIdiom('/path/to/file.xml');
     * 
     * To call an idiom string use: t('KEY')
     * 
     * @param string $filename
     * @param string $module
     * @return \App
     */
    public function loadIdiom($filename, $module = 'app') {
        $idiom = $this->session->_idiom;
        if ($module == 'app') $filename = BASEPATH.'/idiom/'.$idiom.'/'.$filename;
        else $filename = BASEPATH.'/module/'.$module.'/idiom/'.$idiom.'/'.$filename;
        $this->idiom->loadFile($filename);
        return $this;
    }

    /**
     * Returns an idiom translation by key
     * 
     * Examples:
     * echo app()->translate('KEY');
     * echo t('KEY');
     * 
     * Remember to previously load the idiom file with:
     * app()->loadIdiom('/path/to/file.xml');
     * 
     * @param string $key
     * @param array $data
     * @return string
     */
    public function translate($key, $data = array()) {
        return (string) $this->idiom->translate($key, $data);
    }

    /**
     * Verifies the submitted anti-span code
     * Returns false if the code does not match
     * 
     * Example:
     * $secure = app()->getCaptcha() == app()->session->_captcha ? true : false;
     * 
     * @return boolean
     */
    public function getCaptcha() {
        $captcha = $this->session->_captcha;
        $this->session->_captcha = null;
        if ($captcha != $this->input->post('_captcha')) return false;
        return $this->input->post('_captcha');
    }
    
    /**
     * Returns the URL content using cURL
     * This is a GET request
     * 
     * This is a simple way to do a cURL request.
     * Almost no configuration is needed.
     * 
     * Example:
     * $page = app()->httpGet('http://google.com');
     * 
     * @param string $url
     * @param boolean $debug
     * @return string
     */
    public function httpGet($url, $debug = false) {
        return $this->httpPost($url, array(), $debug);
    }

    /**
     * Returns the URL content using cURL
     * This is a POST request
     * 
     * Example:
     * $page = app()->httpPost('http://google.com', array('param' => 1));
     * 
     * @param string $url
     * @param array $post
     * @param boolean $debug
     * @return string
     */
    public function httpPost($url, $post = array(), $debug = false) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $this->log);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    /**
     * Uploads a file
     * Supports multi-file upload
     * Use app()->input->file($index) to get the $_FILES entry
     * 
     * Example:
     * $uploadEntry = app()->input->file($index);
     * $newFile = app()->upload($uploadEntry, '/path/to/dir');
     * 
     * @param array $file
     * @param string $targetDir
     * @return boolean|string
     */
    public function upload($file, $targetDir, $newName = '') {
        if ($file['error']) return false;
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            $this->log('Upload file failed. Directory error: '.$targetDir, 'error');
            return false;
        }
        $name = $file['name'];
        if (!empty($newName)) $name = $newName;
        $destination = $targetDir.'/'.$name;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->log('Failed to move file: '.$destination, 'error');
            return false;
        }
        $this->log('Upload file succeed');
        return $destination;
    }
    
    /**
     * Creates a download attachment Output and exits application
     * 
     * Example:
     * app()->download('/path/to/attachment.pdf');
     * 
     * @param string $filename
     */
    public function download($filename) {
        if (!file_exists($filename)) {
            $this->log('Download failed. File not found: '.$filename, 'error');
            m('File to download was not found', 'alert alert-error');
            app()->redirect(u('/404'));
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $filename);
        $name = basename($filename);
        
        // set Output
        $this->Output = new Output(file_get_contents($filename));
        $this->Output->setHeaders(array(
            'Content-type: '.$type,
            'Content-disposition: attachment; filename='.$name
            ));
        $this->Output->send();
        
        // save session
        $this->session->save();
        $this->log('Attachment sent: '.$filename);
        exit();
    }
    
    /**
     * Returns a new datepicker view
     * @param string $tmpl
     * @return \View_Datepicker
     */
    public function createDatepicker($tmpl = null) {
        return new View_Datepicker($tmpl);
    }
    
    /**
     * Returns a new file upload view
     * @param string $tmpl
     * @return \View_Fileupload
     */
    public function createFileupload($tmpl = null) {
        return new View_Fileupload($tmpl);
    }
    
    /**
     * Returns a new pagination view
     * @param string $id
     * @param string $tmpl
     * @return \View_Pagination
     */
    public function createPagination($id = 1, $tmpl = null) {
        return new View_Pagination($id, $tmpl);
    }
    
    /**
     * Creates a new text editor view
     * @param string $tmpl
     * @return \View_Texteditor
     */
    public function createTexteditor($tmpl = null) {
        return new View_Texteditor($tmpl);
    }
    
    /**
     * Creates a new shopping cart view
     * @param string $tmpl
     * @param Model_Cart $model
     * @return \View_Cart
     */
    public function createCart($tmpl = null, Model_Cart $model = null) {
        return new View_Cart($tmpl, $model);
    }
    
    /**
     * Returns an anti-spam view
     * 
     * Example:
     * echo app()->createCaptcha();
     * 
     * @return View
     */
    public function createCaptcha() {
        $this->session->_captcha = " ";
        $view = new View(BASEPATH.'/theme/default/captcha.php');
        $view->set('code', $this->session->_captcha);
        return $view;
    }
    
    /**
     * Returns a new breadcrumb view
     * @param string $tmpl
     * @return \View_Breadcrumbs
     */
    public function createBreadcrumbs($tmpl = null) {
        return new View_Breadcrumbs($tmpl);
    }
    
    /**
     * Returns a new carousel view
     * @param type $tmpl
     * @return \View_Carousel
     */
    public function createCarousel($tmpl = null) {
        return new View_Carousel($tmpl);
    }
    
    /**
     * Query a database table
     * @param string $tableName
     * @param \PDO $db
     * @return \Table
     */
    public function query($tableName, \PDO $db = null) {
        $table = new Table($tableName, $db);
        return $table;
    }
    
}
