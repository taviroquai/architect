<?php

namespace Arch;

/**
 * Application API
 * 
 * Use alias \Arch\App::Instance() id recomended
 * 
 */
class App implements Messenger
{

    /**
     * Holds user input
     * 
     * To return a GET param: \Arch\App::Instance()->input->get('param');
     * To return all GET param: \Arch\App::Instance()->input->get();
     * To return a POST param use: \Arch\App::Instance()->input->post('param');
     * To return a FILES entry use: \Arch\App::Instance()->input->file($index);
     * To return raw input use: \Arch\App::Instance()->input->raw();
     * 
     * @var Input
     */
    public  $input;
    
    /**
     * Holds application output.
     * This can be a string, a View or a php file (template).
     * 
     * To add HTML use: \Arch\App::Instance()->addContent('<p>Hello World</p>');
     * To add a View use: \Arch\App::Instance()->setContent(new View('tmpl.php');
     * To output a string use: \Arch\App::Instance()->sendOutput('Hello World!');
     * To output a View use: \Arch\App::Instance()->sendOutput(new View('tmpl.php'));
     * @var Output
     */
    public  $output;
    
    /**
     * Default Router
     * 
     * To add a route use: \Arch\App::Instance()->addRoute('/demo', function() { ... });
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
     * You can change the theme with: \Arch\App::Instance()->loadTheme('mytheme');
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
     * Holds the application logger
     * @var \Arch\Logger
     */
    private  $logger;
    
    /**
     * Application singleton instance
     * @var App
     */
    private static $inst = null;

    /**
     * Gets application singleton
     * @param string $filename Full configuration file path
     * @return \App The application singleton
     */
    public static function Instance($filename = 'config.xml')
    {
        if (self::$inst === null) {
            self::$inst = new App($filename);
        }
        return self::$inst;
    }

    /**
     * Returns a new application
     * @param string $filename Full configuration file path
     */
    private function __construct($filename = 'config.xml')
    {
        
        // load configuration and apply
        $config = new Config($filename);
        $config->apply();
        
        // ready to start logging now
        $logpath = BASE_PATH.LOG_PATH.DIRECTORY_SEPARATOR.'log.txt';
        $this->logger = new \Arch\Logger($logpath);
        $this->log('Loaded configuration from '.$filename, 'access', true);
        
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
            \Arch\App::Instance()->output->setHeaders(
                array('HTTP/1.0 400 Not Found', 'Status: 404 Not Found')
                );
            \Arch\App::Instance()
                    ->addContent('<h1>There is no content for this route</h1>');
        });
    }
    
    public function run()
    {
        // load session
        $this->session->load();
        $this->log('Session loaded');
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
        // close resources
        $this->cleanEnd();
    }
    
    private function execute()
    {
        $action = $this->router->getRoute($this->action);
        if ($action === false && $this->action != '/404') {
            $this->redirect($this->url('/404'));
        }
        $this->log('User action: '.$this->action);
        return call_user_func_array($action, $this->input->getParam());
    }
    
    private function initDatabase()
    {
        try {
            $this->db = new \PDO(DB_DSN.';charset=UTF8', DB_USER, DB_PASS);
            $this->db->setAttribute(
                \PDO::ATTR_ERRMODE, 
                \PDO::ERRMODE_EXCEPTION
            );
            $this->log('Database initialized');
        } catch (\PDOException $e)  {
            $this->addMessage (
                'A fatal database error has occured. Try later.', 
                'error'
            );
            $this->log('Database could not be initialized', 'error');
        }
    }
    
    private function loadInput()
    {
        $this->input = new \Arch\Input();
        $this->action = $this->input->getAction();
        $this->log('Input finish loading: '.
                $this->input->server('HTTP_USER_AGENT'));
        if ($this->input->get('idiom')) {
            $this->session->_idiom = $this->input->get('idiom');
        } else {
            $this->session->_idiom = IDIOM;
        }
        $this->idiom = new \Arch\Idiom($this->session->_idiom);
        $filename = 'default.xml';
        $this->loadIdiom($filename);
    }
    
    private function loadModules()
    {
        $mods = glob(BASE_PATH.'/module/enable/*');
        foreach($mods as $name) {
            if (is_dir($name)) {
                if (is_dir($name.'/src')) {
                    $includes = glob($name.'/src/*.php');
                    foreach($includes as $inc) require_once $inc;
                }
                $filename = $name.'/config.php';
                if (file_exists($filename)) {
                    require_once $filename;
                    \Arch\App::Instance()->log('Module loaded: '.$name);
                }
            }
        }
        $this->modules = $mods;
    }
    
    private function cleanEnd() {
        
        // close output buffer
        if (ob_get_status()) ob_end_flush();
        
        // save session
        $this->session->save();
        $this->log('Session closed');
        
        // close log handler
        $this->logger->close();
    }
    
    
    private function sendOutput()
    {
        if (get_class($this->output->getContent()) === FALSE) {
            if ($this->output->getContent() == '') {
                $this->output->setContent($this->theme);
            }
        }
        
        // clean application buffer; only 1 output allowed
        // not good for debugging; please use app()->log($msg) for debugging
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // send output
        $this->log('Sending output...');
        if (!$this->input->isCli()) $this->output->sendHeaders();
        $this->output->send();
    }
    
    /**
     * Logs application activity
     * If LOG_PATH is empty, no log happens
     * 
     * @param string $msg The message to be logged
     * @param string $label Label for the log message
     * @param boolean $nlb If true adds a line break
     * @return boolean
     */
    public function log($msg, $label = 'access', $nlb = false)
    {
        return $this->logger->log($msg, $label, $nlb);
    }
    
    /**
     * Loads the theme configuration
     * 
     * For example:
     * \Arch\App::Instance()->loadTheme('mytheme');
     * 
     * This will load /theme/mytheme/config.php and /theme/mytheme/theme.xml
     * 
     * Theme slots can be configured with theme.xml without programming
     * Remember that modules that are not enable will not be displayed
     * 
     * You can also add content with, for example:
     * \Arch\App::Instance()->theme->addContent('Hello World');
     * 
     * @param type $name
     * @return \App
     */
    public function loadTheme($name)
    {
        $theme_path = BASE_PATH.'/theme/'.$name;

        $filename = $theme_path.'/config.php';
        if (file_exists($filename)) {
            require_once $filename;
        }
        
        $filename = $theme_path.'/slots.xml';
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->slot as $slot) {
                $slotName = (string) $slot['name'];
                foreach ($slot->module as $item) {
                    $classname = (string) $item->classname;
                    if (!class_exists($classname)) {
                        continue;
                    }
                    $c = isset($item->content) ? (string) $item->content : '';
                    $module = new $classname($c);
                    $this->theme->addContent($module, $slotName);
                }
            }
            $this->log('Theme loaded: '.$name);
        }
        return $this;
    }
    
    /**
     * Sets application output
     * 
     * This is a fast way to send text output
     * It will use an Output instance to send
     * 
     * Example:
     * app()->output('Hello World!');
     * 
     * @param mixed $content
     */
    public function output($content)
    {
        $this->output->setContent($content);
    }
    
    /**
     * Sends a redirect HTTP header
     * 
     * This will send an HTTP location header and exit application
     * if now is true
     * 
     * Example:
     * \Arch\App::Instance()->redirect(\Arch\App::Instance()->url('/demo'));
     * 
     * @param string $url
     * @param boolean $now
     */
    public function redirect($url = null, $now = true)
    {
        if ($this->url($this->action) == $url) return; 
        if (empty($url)) {
            $url = $this->url('/');
        }
        $output = new \Arch\Output();
        $output->setHeaders(array('Location: '.$url));
        $output->sendHeaders();
        $output->send();
        $this->log('Redirecting to '.$url);
        if ($now) {
            $this->session->save();
            exit();
        }
    }
    
    /**
     * Creates a JSON response, sends it and exits
     * @param array $data
     * @param boolean $cache
     */
    public function sendJSON($data, $cache = false) {
        $headers = array();
        if (!$cache) {
            $headers[] = 'Cache-Control: no-cache, must-revalidate';
            $headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
        }
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $this->output->setHeaders($headers);
        $this->output->setContent(json_encode($data));
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
     * \Arch\App::Instance()->addRoute('/', function() { 
     *     \Arch\App::Instance()->sendOutput('Home'); 
     * });
     * 
     * @param string $key
     * @param function $action
     * @return \App
     */
    public function addRoute($key, $action)
    {
        $result = $this->router->addRoute($key, $action);
        if (!$result) {
            $this->log('Add route failed: '.$key);
        }
        return $this;
    }

    /**
     * Adds a message to session that can be shown in a view
     * 
     * Messages are very important. A message can be added to the theme in any 
     * part of the application.
     * 
     * Example:
     * \Arch\App::Instance()->addMessage('Correct answer!');
     * 
     * @param string $text
     * @param string $cssClass
     * @return \App
     */
    public function addMessage($text, $cssClass = 'alert alert-success')
    {
        $this->session->addMessage($text, $cssClass);
        return $this;
    }

    /**
     * Returns session messages
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->session->getMessages();
    }
    
    /**
     * Uses a template to display the messages
     * 
     * This is commonly used in themes to display and clear the messages
     * 
     * Example:
     * \Arch\App::Instance()->showMessages(function($item) {
     *     echo '<div>'.$item.'</div>';
     * });
     * 
     * @param function $template
     * @param boolean $flush
     * @return \App
     */
    public function showMessages($template, $flush = true)
    {
        if (count($this->session->getMessages()) == 0) {
            return;
        }
        $messages = $this->session->getMessages();
        foreach ($messages as $item) {
            $template($item);
        }
        if ($flush) {
            $this->session->clearMessages();
        }
        return $this;
    }

    /**
     * Flushes session messages
     * @return \App
     */
    public function clearMessages()
    {
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
     * \Arch\App::Instance()
     *  ->addEvent('demo.form.after.post', function($target = null) {
     *     // do something with target
     * });
     * 
     * And then, this will be called by:
     * \Arch\App::Instance()
     *  ->triggerEvent('demo.form.after.post', $target); // optional target
     * 
     * @param string $eventName
     * @param function $callback
     * @param mixed $target
     * @return \App
     */
    public function addEvent($eventName, $callback, $target = null)
    {
        if ($target === null) {
            $target = $this;
        }
        if (!is_callable($callback)) {
            $this->log('Event create failed: '.$eventName);
        } else {
        $this->events[$eventName][] = 
                new \Arch\Event($eventName, $callback, $target);
        }
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
    public function triggerEvent($eventName, $target = null)
    {
        if (isset($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $evt) {
                $this->log('Event triggered: '.$eventName);
                $evt->trigger($target);
            }
        }
        return $this;
    }
    
    /**
     * Adds content to the default theme
     * 
     * This is the common way to show output to the user. USE IT!
     * 
     * Examples:
     * \Arch\App::Instance()->addContent('Hello World');
     * \Arch\App::Instance()->addContent('/path/to/template.php');
     * \Arch\App::Instance()->addContent(new View('/path/to/template.php');
     * 
     * @param mixed $content
     * @param string $slotName
     * @param boolean $unique
     * @return \App
     */
    public function addContent($content, $slotName = 'content', $unique = false)
    {
        $this->theme->addContent($content, $slotName, $unique);
        return $this;
    }

    /**
     * Builds and returns an internal url
     * 
     * Example:
     * $url = \Arch\App::Instance()->url('/list', array('page' => 1));
     * 
     * @param string $path
     * @param array $params
     * @return string
     */
    public function url($path = '', $params = array())
    {
        $base = INDEX_FILE == '' ? rtrim(BASE_URL, '/') : BASE_URL;
        $uri = empty($path) ? '' : $path;
        $query = empty($params) ? '' : '?';
        $query .= http_build_query($params);
        return $base.INDEX_FILE.$uri.$query;
    }
    
    /**
     * Returns an hash of a string
     * 
     * Instead of using diferent encryptions spread in the application,
     * use this centralized method.
     * 
     * Example:
     * $password = \Arch\App::Instance()->encrypt('password');
     * 
     * @param string $string
     * @param string $algo
     * @param string $salt
     * @return string
     */
    public function encrypt($string, $algo = 'sha256', $salt = '!Zz$9y#8x%7!')
    {
        if (in_array($algo, hash_algos())) {
            return hash($algo, $string);
        }
        return (string) md5($string);
    }

    /**
     * Sends an email
     * 
     * Don't worry to configure an email request, but use this method.
     * This uses the phpmailer library.
     * 
     * Example:
     * $result = \Arch\App::Instance()->mail(
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
    public function mail($to, $subject, $view)
    {
        require_once 'vendor/phpmailer/class.phpmailer.php';
        
        try {
            $mail = new \PHPMailer(true); // defaults to using php "mail()"
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom(MAIL_FROM, MAIL_FROMNAME);
            $mail->AddReplyTo(MAIL_REPLY, MAIL_REPLYNAME);
            $mail->AddAddress($to);
            $mail->Subject = $subject;
            $mail->AltBody = "Please use an HTML email viewer!";
            $mail->MsgHTML((string) $view);
            $result = $mail->Send();
            $this->log("Sending mail to $to has succeed");
        }
        catch (phpmailerException $e) {
            $this->log(
                "Sending mail to $to failed: ".$e->errorMessage(), 
                'error'
            );
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
     * \Arch\App::Instance()->loadIdiom('/path/to/file.xml');
     * 
     * To call an idiom string use: t('KEY')
     * 
     * @param string $filename
     * @param string $module
     * @return \App
     */
    public function loadIdiom($filename, $module = 'app')
    {
        $idiom = $this->session->_idiom;
        if ($module == 'app') {
            $filename = BASE_PATH.'/idiom/'.$idiom.'/'.$filename;
        } else {
            $filename = BASE_PATH.'/module/'.$module.'/idiom/'.
                    $idiom.'/'.$filename;
        }
        if ($this->idiom->loadFile($filename)) {
            $this->log('Idiom file loaded: '.$filename);
        } else {
            $this->log('Idiom file load failed: '.$filename, 'error');
        }
        return $this;
    }

    /**
     * Returns an idiom translation by key
     * 
     * Examples:
     * echo \Arch\App::Instance()->translate('KEY');
     * echo t('KEY');
     * 
     * Remember to previously load the idiom file with:
     * \Arch\App::Instance()->loadIdiom('/path/to/file.xml');
     * 
     * @param string $key
     * @param array $data
     * @return string
     */
    public function translate($key, $data = array())
    {
        return (string) $this->idiom->translate($key, $data);
    }

    /**
     * Verifies the submitted anti-span code
     * Returns false if the code does not match
     * 
     * Example:
     * $secure = \Arch\App::Instance()->getCaptcha();;
     * 
     * @return boolean
     */
    public function getCaptcha()
    {
        $captcha = $this->session->_captcha;
        $this->session->_captcha = null;
        if ($captcha != $this->input->post('_captcha')) {
            return false;
        }
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
     * $page = \Arch\App::Instance()->httpGet('http://google.com');
     * 
     * @param string $url
     * @param boolean $debug
     * @return string
     */
    public function httpGet($url, $debug = false)
    {
        return $this->httpPost($url, array(), $debug);
    }

    /**
     * Returns the URL content using cURL
     * This is a POST request
     * 
     * Example:
     * $page = \Arch\App::Instance()->httpPost(
     *  'http://google.com', 
     *  array('param' => 1)
     * );
     * 
     * @param string $url
     * @param array $post
     * @return string
     */
    public function httpPost($url, $post = array())
    {
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
     * Use \Arch\App::Instance()->input->file($index) to get the $_FILES entry
     * 
     * Example:
     * $uploadEntry = \Arch\App::Instance()->input->file($index);
     * $newFile = \Arch\App::Instance()->upload($uploadEntry, '/path/to/dir');
     * 
     * @param array $file File entry from \Arch\App::Instance()->input->file()
     * @param string $targetDir Full target directory
     * @param string $newName New name to the uploaded file
     * @return boolean|string
     */
    public function upload($file, $targetDir, $newName = '')
    {
        if ($file['error']) {
            return false;
        }
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            $this->log(
                'Upload file failed. Directory error: '.$targetDir, 
                'error'
            );
            return false;
        }
        $name = $file['name'];
        if (!empty($newName)) {
            $name = $newName;
        }
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
     * \Arch\App::Instance()->download('/path/to/attachment.pdf');
     * 
     * @param string $filename
     */
    public function download($filename, $attachment = true)
    {
        if (!file_exists($filename)) {
            $this->log('Download failed. File not found: '.$filename, 'error');
            $this->addMessage(
                'File to download was not found',
                'alert alert-error'
            );
            \Arch\App::Instance()->redirect($this->url('/404'));
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $filename);
        $name = basename($filename);
        
        // set output
        $this->output->setContent(file_get_contents($filename));
        $headers = array();
        $headers[] = 'Content-type: '.$type;
        if ($attachment) {
            $headers[] = 'Content-disposition: attachment; filename='.$name;
        }
        $this->output->setHeaders($headers);
    }
    
    /**
     * Returns a new datepicker view
     * @param string $tmpl
     * @return \View_Datepicker
     */
    public function createDatepicker($tmpl = null)
    {
        return new \Arch\View\Datepicker($tmpl);
    }
    
    /**
     * Returns a new file upload view
     * @param string $tmpl
     * @return \View_Fileupload
     */
    public function createFileupload($tmpl = null)
    {
        return new \Arch\View\Fileupload($tmpl);
    }
    
    /**
     * Returns a new pagination view
     * @param string $id
     * @param string $tmpl
     * @return \View_Pagination
     */
    public function createPagination($id = 1, $tmpl = null)
    {
        return new \Arch\View\Pagination($id, $tmpl);
    }
    
    /**
     * Creates a new text editor view
     * @param string $tmpl
     * @return \View_Texteditor
     */
    public function createTexteditor($tmpl = null)
    {
        return new \Arch\View\Texteditor($tmpl);
    }
    
    /**
     * Creates a new shopping cart view
     * @param string $tmpl
     * @param Model_Cart $model
     * @return \View_Cart
     */
    public function createCart($tmpl = null, Model_Cart $model = null)
    {
        if ($tmpl == null) $tmpl = BASE_PATH.'/theme/default/cart.php';
        if ($model === null) $model = new \Arch\Model\Cart ($this->session);
        return new \Arch\View\Cart($tmpl, $model);
    }
    
    /**
     * Returns an anti-spam view
     * 
     * Example:
     * echo \Arch\App::Instance()->createCaptcha();
     * 
     * @return View
     */
    public function createCaptcha()
    {
        $this->session->_captcha = " ";
        $view = new \Arch\View(BASE_PATH.'/theme/default/captcha.php');
        $view->set('code', $this->session->_captcha);
        return $view;
    }
    
    /**
     * Returns a new breadcrumb view
     * @param string $tmpl
     * @return \View_Breadcrumbs
     */
    public function createBreadcrumbs($tmpl = null)
    {
        return new \Arch\View\Breadcrumbs($tmpl);
    }
    
    /**
     * Returns a new carousel view
     * @param type $tmpl
     * @return \View_Carousel
     */
    public function createCarousel($tmpl = null)
    {
        return new \Arch\View\Carousel($tmpl);
    }
    
    /**
     * Returns a new FTP server connection
     * @param string $host The remote host
     * @param string $username The ftp username
     * @param string $password The ftp password
     * @return boolean The connection result
     */
    public function createFTP($host="", $username="", $password="")
    {
        $this->log('FTP connected to '.$host);
        $ftp = new FTP($host, $username, $password);
        if (!$ftp->connect($password)) {
            $this->log('FTP connect to '.$host.' failed', 'error');
        } else {
            $this->log('FTP connected to '.$host);
        }
        return $ftp;
    }
    
    /**
     * Returns a new input validator
     * @return \Arch\Validator
     */
    public function createValidator()
    {
        return new \Arch\Validator($this);
    }
    
    /**
     * Returns a new comment form
     * @param string $tmpl The template file path
     * @return \Arch\View\CommentForm
     */
    public function createCommentForm($tmpl = null)
    {
        return new \Arch\View\CommentForm($tmpl);
    }
    
    /**
     * Returns a new map view
     * @param string $tmpl The template for the map
     * @return \Arch\View\Map
     */
    public function createMap($tmpl = null, \Arch\Model\Map $model = null)
    {
        return new \Arch\View\Map($tmpl, $model);
    }
    
    /**
     * Returns a new Line Chart view
     * @param string $tmpl The chart template file path
     * @return \Arch\View\LineChart
     */
    public function createLineChart($tmpl = null)
    {
        return new \Arch\View\LineChart($tmpl);
    }
    
    /**
     * Returns a new Tree view
     * @param string $tmpl The template for the tree
     * @return \Arch\View\TreeView
     */
    public function createTreeView($tmpl = null)
    {
        return new \Arch\View\TreeView($tmpl);
    }
    
    /**
     * Returns a new File Explorer view
     * @param string $tmpl The template for explorer
     * @return \Arch\View\FileExplorer
     */
    public function createFileExplorer($tmpl = null)
    {
        return new \Arch\View\FileExplorer($tmpl);
    }
    
    /**
     * Returns a new poll view
     * @param string $tmpl The template for the poll
     * @return \Arch\View\Poll
     */
    public function createPoll($tmpl = null)
    {
        return new \Arch\View\Poll($tmpl);
    }

        /**
     * Query a database table
     * @param string $tableName
     * @param \PDO $db
     * @return \Table
     */
    public function query($tableName)
    {
        if (empty($this->db)) $this->initDatabase ();
        $table = new \Arch\Table($tableName, $this->db);
        return $table;
    }
}
