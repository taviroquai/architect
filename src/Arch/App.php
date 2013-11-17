<?php

namespace Arch;

/**
 * Define Architect base path
 */
define('ARCH_PATH', realpath(__DIR__ . '/../../'));

/**
 * Application API
 * 
 * Use alias \Arch\App::Instance() is recomended
 * 
 */
class App implements Messenger
{

    /**
     * Holds user input.
     * 
     * To return a GET param use <b>app()->input->get('param')</b> or 
     * <b>g('param')</b>.
     * 
     * To return all GET params use <b>app()->input->get()</b> or <b>g()</b>.
     * 
     * To return a POST param use <b>app()->input->post('param')</b> or
     * <b>p('param')</b>.
     * 
     * To return a FILES entry use <b>app()->input->file($index)</b> or 
     * <b>f($index)</b>.
     * 
     * To return raw input use <b>app()->input->raw()</b>
     * 
     * @var \Arch\Input The application input object
     */
    public  $input;
    
    /**
     * Holds application output.
     * 
     * To add HTML use <b>app()->addContent('&lt;p&gt;Hello World&lt;/p&gt;')</b>.
     * 
     * To add a View use <b>app()->setContent(new View('tmpl.php')</b>.
     * 
     * To output a string use <b>app()->sendOutput('Hello World!')</b> or 
     * <b>o('Hello World!')</b>.
     * 
     * To output a View use <b>app()->sendOutput(new View('tmpl.php'))</b> or
     * using alias <b>o(v('template.php'))</b>.
     * 
     * @var \Arch\Output The application output
     */
    public  $output;
    
    /**
     * Holds the URI Router.
     * 
     * To add a route use <b>app()->addRoute('/demo', function() { ... })</b> or
     * <b>r('/demo', function() { ... })</b>.
     * 
     * The function callback will be called when the user requests
     * <b>index.php/demo</b>
     * 
     * @var \Arch\Router The URI router
     */
    public  $router;
    
    /**
     * Holds the default theme.
     * 
     * This will hold the theme (Theme) that will be used when outputing HTML.
     * 
     * You can change the theme with <b>app()->loadTheme($path)</b>.
     * 
     * This will load the theme configuration into the application
     * 
     * @var \Arch\Theme The theme object
     */
    public  $theme;
    
    /**
     * Holds user session.
     * 
     * Session variables can be set using <b>app()->session->param = 'value'</b>
     * 
     * To use the native PHP session, you have to manually call 
     * <b>session_start()</b> on the 'arch.session.before.load' event. Like this
     * 
     * <b>e('arch.session.before.load', function() { session_start(); });</b>
     * 
     * You can override the Session object by your own.
     * 
     * @var \Arch\Session
     */
    public  $session;
    
    /**
     * Holds PDO database instance.
     * 
     * This is lazy loading; it will be loaded if the user starts a query
     * using <b>q('tablename')</b> or <b>app()->query('tablename')</b>.
     * 
     * When this is requested, the application will look for the <b>default
     * database constants</b> defined in configuration.
     * 
     * Database constants are:
     * <ul>
     * <li>DB_DRIVER - defines DSN driver string</li>
     * <li>DB_DATABASE - defined the default database</li>
     * <li>DB_HOST - defined the default database host</li>
     * <li>DB_USER - defined PDO user</li>
     * <li>DB_PASS - defined user password</li>
     * </ul>
     * 
     * @var \DBDriver The default database driver
     */
    public  $db;
    
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
     * Holds the current running stage
     * @var string
     */
    private  $stage = '';
    
    /**
     * Application singleton instance
     * @var \Arch\App
     */
    private static $inst = null;

    /**
     * Gets application singleton
     * @param string $filename Full configuration file path
     * @return \Arch\App The application singleton
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
        // update stage
        $this->stage = 'init';
        
        // load configuration and apply
        try {
            $config = new Config();
            $config->load($filename);
            $config->apply();
        }
        catch (\Exception $e) {
            die($e->getMessage());
        }
        
        // ready to start logging now
        $this->logger = new \Arch\Logger(LOG_FILE);
        $this->log('Loaded configuration from '.$filename, 'access', true);
        
        // set session handler
        $this->session = new Session();

        // set default output
        $this->theme = new View();
        $this->theme->addSlot('css')->addSlot('js');
        
        // set input
        $this->input = new \Arch\Input();
        if ($_GET) $this->input->setHttpGet ($_GET);
        if ($_POST) $this->input->setHttpPost ($_POST);
        if (!empty($_FILES)) $this->input->setHttpFiles($_FILES);
        $this->input->setRawInput(file_get_contents("php://input"));
        $this->input->parseGlobal(php_sapi_name(), $_SERVER);
        $this->input->getAction();
        $this->log('Input finish loading: '.
                $this->input->server('HTTP_USER_AGENT'));
        unset($_SERVER);
        unset($_GET);
        unset($_POST);
        unset($_FILES);

        // set default Output
        $this->output = new Output();
        
        // set default routes
        $this->router = new Router($this);
        $this->addCoreRoutes();
    }    
    
    /**
     * Runs the application through various stages.
     * 
     * It can only be called once.
     * 
     * @return null
     */
    public function run()
    {
        // prevent infinit calls
        if ($this->stage === 'run') return;
        
        // update stage
        $this->stage = 'run';
        
        // bypass user modules if it is a core action (arch)
        // main purpose is to improve performance
        if (!$this->input->isArchAction()) {
            // load enabled modules
            $this->loadModules();
        }

        // trigger core event
        $this->triggerEvent('arch.session.before.load');
        $this->session->load();
        $this->log('Session loaded');

        // load default theme if exists
        if (defined('DEFAULT_THEME')) {
            $this->loadTheme(THEME_PATH.DIRECTORY_SEPARATOR.DEFAULT_THEME);
        }

        // execute action
        $this->execute();

        // send output
        $this->sendOutput();

        // close resources
        $this->cleanEnd();
    }
    
    /**
     * Loads the theme configuration.
     * 
     * Use it as <b>app()->loadTheme('/mytheme/')</b>
     * 
     * This will load <b>/theme/mytheme/config.php</b> and 
     * <b>/theme/mytheme/slots.xml</b>.
     * 
     * Theme slots can be configured with <b>slots.xml</b> without programming
     * skills.
     * 
     * Remember that modules that are not enable will not be displayed.
     * 
     * To add content do <b>app()->addContent('Hello World')</b>
     * 
     * @param string $path The theme path
     * 
     * @return \Arch\App The main application
     */
    public function loadTheme($path)
    {
        $this->theme = new \Arch\Theme($path, $this);
        // create a default idiom loader
        $this->theme->set('idiom', $this->createIdiom());
        // add flash messages
        $this->theme->set(
            'messages',
            new \Arch\View($path.DIRECTORY_SEPARATOR.'messages.php')
        );
        
        // trigger core event
        $this->triggerEvent('arch.theme.after.load', $this->theme);
        
        $this->log('Theme loaded: '.$path);
        return $this;
    }
    
    /**
     * Logs application activity.
     * 
     * If LOG_FILE is empty, no log happens
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
     * Sets application output.
     * 
     * This is a fast way to send text output. It will use the Output instance 
     * to set content
     * 
     * Use it as <b>app()->output('Hello World!')</b> or 
     * <b>o('Hello World!')</b>
     * 
     * @param mixed $content This can be a string or a View
     */
    public function output($content)
    {
        $this->output->setContent($content);
    }
    
    /**
     * Sends a redirect HTTP header.
     * 
     * This will send an HTTP location header and exit application
     * if now is true
     * 
     * Use it as <b>app()->redirect(app()->url('/demo'))</b> or 
     * <b>r(u('/demo'))</b>.
     * 
     * @param string $url The URL to redirect to
     * @param boolean $now If true, just exit, do not proceed to the next stage
     */
    public function redirect($url = null, $now = true)
    {
        if ($this->url($this->input->getAction()) == $url) return; 
        if (empty($url)) {
            $url = $this->url('/');
        }
        $output = new \Arch\Output();
        $output->setHeaders(array('Location: '.$url));
        $output->sendHeaders();
        $output->send();
        $this->log('Redirecting to '.$url);
        if ($now) {
            $this->cleanEnd();
            exit();
        }
    }
    
    /**
     * Creates a JSON response, sends it and exits.
     * 
     * You can also use it as <b>j(array('hello' => 'world'))</b>.
     * 
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
     * Allows to add a route and a callback.
     * 
     * Use it as:
     * <b>
     * app()->addRoute('/', function() { 
     *     \Arch\App::Instance()->sendOutput('Home'); 
     * });
     * </b>
     * 
     * When the user calls in the browser <b/>index.php/demo</b>, the 
     * application will look for a route with key <b>/demo</b> and call the 
     * action callback.
     * 
     * You should add a default route like this:
     * <b>
     * r('/', function() {
     *      o('Home');
     * });
     * </b>
     * 
     * @param string $key The input key
     * @param function $action The route callback
     * @return \Arch\App The application
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
     * Adds a message to session that can be shown in a view.
     * 
     * Messages are very important. A message can be added to the theme in any 
     * part of the application.
     * 
     * Use it as <b>app()->addMessage('Correct answer!')</b> or 
     * <b>m('Incorrect!', 'alert alert-error')</b>
     * 
     * @param string $text The message body
     * @param string $cssClass The css class to be used in theme
     * @return \Arch\App The application
     */
    public function addMessage($text, $cssClass = 'alert alert-success')
    {
        $this->session->addMessage(new \Arch\Message($text, $cssClass));
        return $this;
    }

    /**
     * Returns session messages.
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->session->getMessages();
    }

    /**
     * Flushes session messages.
     * 
     * @return \Arch\App
     */
    public function clearMessages()
    {
        $this->session->clearMessages();
        return $this;
    }
    
    /**
     * Adds an event and a callback.
     * 
     * This is very usefull to interact with other modules by not having to
     * hack modules code.
     * 
     * Example:
     * <b>
     * app()->addEvent('demo.form.after.post', function($target = null) {
     *     // do something with target
     * });
     * </b>
     * 
     * And then, this will be called by:
     * <b>
     * app()->triggerEvent('demo.form.after.post', $target); // optional target
     * </b>
     * 
     * @param string $eventName The event name
     * @param function $callback The event callback
     * @param mixed $target An optional target
     * @return \Arch\App The application
     */
    public function addEvent($eventName, $callback, $target = null)
    {
        if ($target === null) {
            $target = $this;
        }
        try {
            $evt = new \Arch\Event($eventName, $callback, $target);
            $this->events[$eventName][] = $evt;
        } catch (\Exception $e) {
            $this->log('Event create failed: '.$e->getMessage(), 'error');
        }
        
        return $this;
    }
    
    /**
     * Triggers an event by name.
     * 
     * Passes an option $target object
     * 
     * @param string $eventName The event name
     * @param mixed $target An optional target variable
     * @return \Arch\App The application
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
     * Adds content to the default theme. USE IT!
     * 
     * Examples:
     * 
     * <b>c('Hello World')</b>
     * 
     * <b>c(new \My\View())</b>
     * 
     * <b>c(v('my_template.php'))</b>
     * 
     * <b>app()->addContent('Hello World')</b>
     * 
     * <b>app()->addContent('/path/to/template.php')</b>
     * 
     * <b>app()->addContent(new View('/path/to/template.php')</b>
     * 
     * @param mixed $content The content to be added
     * @param string $slotName The slot name, defaults to content
     * @param boolean $unique If true, other equal contents will be ignored
     * @return \Arch\App The application
     */
    public function addContent($content, $slotName = 'content', $unique = false)
    {
        $this->theme->addContent($content, $slotName, $unique);
        return $this;
    }

    /**
     * Builds and returns an internal url.
     * 
     * Example:
     * 
     * <b>app()->url('/list', array('page' => 1))</b> will generate 
     * <b>/index.php/list?page=1</b>
     * 
     * @param string $action The route action
     * @param array $params An associative array with params
     * @return string The resulting URL
     */
    public function url($action = '', $params = array())
    {
        $base = INDEX_FILE == '' ? rtrim(BASE_URL, '/') : BASE_URL.'/';
        $uri = empty($action) ? '' : $action;
        $query = empty($params) ? '' : '?';
        $query .= http_build_query($params);
        return $base.INDEX_FILE.$uri.$query;
    }
    
    /**
     * Returns an hash of a string.
     * 
     * Instead of using diferent encryptions spread in the application,
     * use this centralized method.
     * 
     * Use it as <b>app()->encrypt('password')</b> or just <b>s('password')</b>
     * 
     * @param string $string The string to be secured
     * @param string $algo An hash algorithmn
     * @return string The secured string
     */
    public function encrypt($string, $algo = 'sha256')
    {
        if (in_array($algo, hash_algos())) {
            return hash($algo, $string);
        }
        return (string) md5($string);
    }

    /**
     * Verifies the submitted anti-span code.
     * 
     * Returns false if the code does not match.
     * 
     * Use it as <b>app()->getCaptcha()</b>
     * 
     * @return boolean!string If true the user input matches
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
     * Returns the URL content using cURL.
     * 
     * This is a GET request
     * 
     * Use it as <b>app()->httpGet('http://google.com')</b>
     * 
     * @param string $url The target URL
     * @param boolean $debug If true, debug information will be logged
     * @return string The response body
     */
    public function httpGet($url, $debug = false)
    {
        return $this->httpPost($url, array(), $debug);
    }

    /**
     * Returns the URL content using cURL.
     * 
     * This is a POST request
     * 
     * Use it as:
     * 
     * <b>app()->httpPost(
     *  'http://google.com', 
     *  array('param' => 1)
     * )</b>
     * 
     * @param string $url The target URL
     * @param array $post The data to be posted
     * @return string The response body
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
     * Uploads a file. Supports multi-file upload.
     * 
     * Use it as:
     * 
     * $uploadEntry = app()->input->file($index);
     * 
     * <b>
     * $newFile = app()->upload($uploadEntry, '/path/to/dir', 'newname');
     * </b>
     * 
     * Where $index is the index of the $_FILES entry
     * 
     * @param array $file File entry from app()->input->file()
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
     * Use it as <b>app()->download('/path/to/attachment.pdf')</b>
     * 
     * @param string $filename The file to be donwloaded
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
     * Returns a safe url string.
     * 
     * @param string $text The string to be translated
     * @return string The resulting string
     */
    public function slug($text)
    { 
        $slug = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $slug = trim($slug, '-');
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = strtolower($slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        if (empty($slug)) {
            $slug = md5($text);
        }
        return $slug;
    }
    
    /**
     * Returns a new query on a database table (PDO).
     * 
     * Helps to get and put data onto a database.
     * 
     * @param string $tableName The name of the table
     * @return \Arch\Table The table object
     */
    public function createQuery($tableName)
    {
        if (empty($this->db)) $this->initDatabase ();
        $table = $this->db->createTable($tableName);
        return $table;
    }
    
    /**
     * Returns a new automatic table
     * @param array $config The configuration
     * @return \Arch\View\AutoPanel\AutoTable
     */
    public function createAutoTable($config)
    {
        if (empty($this->db)) $this->initDatabase ();
        return new \Arch\View\AutoPanel\AutoTable($config, $this->db);
    }
    
    /**
     * Returns a new automatic form
     * @param array $config The configuration
     * @return \Arch\View\AutoPanel\AutoForm
     */
    public function createAutoForm($config)
    {
        if (empty($this->db)) $this->initDatabase ();
        return new \Arch\View\AutoPanel\AutoForm($config, $this->db);
    }
    
    /**
     * Returns a new image.
     * 
     * It helps to create thumbs.
     * 
     * @param string $filename The image file path
     * @return \Arch\Image
     */
    public function createImage($filename)
    {
        return new \Arch\Image($filename);
    }
    
    /**
     * Returns a new Idiom object.
     * 
     * Helps to get translations from idiom files (.xml)
     * 
     * @param string $code The ISO code
     * @return \Arch\Idiom The idiom object
     */
    public function createIdiom(
            $code = null, 
            $name = 'default.xml', 
            $module = 'app'
    ) {
        // resolve idiom code
        if (empty($code)) {
            $code = $this->input->get('idiom');
            if (empty($code)) {
                if (defined('DEFAULT_IDIOM')) {
                    $code = DEFAULT_IDIOM;
                } else {
                    $code = 'en';
                }
            }
            $this->session->idiom = $code;
        }
        $idiom = new \Arch\Idiom($code);
        $filename = $idiom->resolveFilename($name, $module);
        if (!$idiom->loadTranslation($filename)) {
            $this->log('Translation failed: '.$filename);
        }
        
        // trigger core event
        $this->triggerEvent('arch.idiom.after.load', $this);
        return $idiom;
    }
    
    /**
     * Returns a new input validator.
     * 
     * Helps to validate user input.
     * 
     * Use it as:
     * 
     * <b>
     * $v = app()->createValidator();
     * 
     * $rule = $v->createRule('email')->setAction('isEmail');
     * 
     * $v->addRule($rule);
     * 
     * $result = $v->validate()->getResult();
     * 
     * app()->session->loadMessages($v->getMessages());
     * </b>
     * 
     * @return \Arch\Validator The validator object
     */
    public function createValidator()
    {
        $type = $this->input->server('REQUEST_METHOD');
        $input = $this->input->{$type}();
        return new \Arch\Validator($input);
    }
    
    /**
     * Returns a new view for the given template.
     * 
     * The view adds methods to allow data manipulation on the template.
     * 
     * @param string $tmpl The template path
     * @param array $data The associative array with data
     * @return \Arch\View
     */
    public function createView($tmpl, $data = array())
    {
        return new \Arch\View($tmpl, $data);
    }
    
    /**
     * Returns a new date picker view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The date picker template
     * @return \Arch\View\DatePicker
     */
    public function createDatePicker($tmpl = null)
    {
        $view = new \Arch\View\DatePicker($tmpl);
        // add view resources
        $this->addContent(
            $this->url('/arch/asset/css/bootstrap-datetimepicker.min.css'),
            'css'
        );
        $this->addContent(
            $this->url('/arch/asset/js/bootstrap-datetimepicker.min.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new file upload view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The upload template
     * @return \Arch\View\FileUpload
     */
    public function createFileUpload($tmpl = null)
    {
        $view = new \Arch\View\FileUpload($tmpl);
        // add view resources
        $this->addContent(
            $this->url('/arch/asset/css/bootstrap-fileupload.min.css'),
            'css'
        );
        $this->addContent(
            $this->url('/arch/asset/js/bootstrap-fileupload.min.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new pagination view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $id The view ID
     * @param string $tmpl The pagination template file path
     * @return \Arch\View\Pagination
     */
    public function createPagination($id = 1, $tmpl = null)
    {
        $view = new \Arch\View\Pagination($id, $tmpl);
        $view->parseCurrent($this->input);
        return $view;
    }
    
    /**
     * Creates a new text editor view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The editor template
     * @return \Arch\View\TextEditor
     */
    public function createTextEditor($tmpl = null)
    {
        $view = new \Arch\View\TextEditor($tmpl);
        // add view resources
        $this->addContent(
            $this->url('/arch/asset/css/font-awesome.min.css'),
            'css'
        );
        $this->addContent(
            $this->url('/arch/asset/css/wysiwyg.css'),
            'css'
        );
        $this->addContent(
            $this->url('/arch/asset/js/jquery.hotkeys.js'),
            'js'
        );
        $this->addContent(
            $this->url('/arch/asset/js/bootstrap-wysiwyg.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Creates a new shopping cart view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template file
     * @param \Arch\Model\Cart $model The shopping cart model
     * @return \Arch\View\Cart
     */
    public function createCart($tmpl = null, \Arch\Model\Cart $model = null)
    {
        if ($model === null) {
            $model = new \Arch\Model\Cart ($this->session);
        }
        return new \Arch\View\Cart($tmpl, $model);
    }
    
    /**
     * Returns an anti-spam view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @return \Arch\View
     */
    public function createCaptcha($tmpl = null)
    {
        $this->session->_captcha = " ";
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH, 'theme', 'architect', 'captcha.php'));
        $view = new \Arch\View($tmpl);
        $view->set('code', $this->session->_captcha);
        return $view;
    }
    
    /**
     * Returns a new breadcrumb view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template file path
     * @param boolena $parseInput Tells to insert items from input
     * @return \Arch\View\Breadcrumbs
     */
    public function createBreadcrumbs($tmpl = null, $parseInput = true)
    {
        $view = new \Arch\View\Breadcrumbs($tmpl);
        if ($parseInput) {
            $view->parseAction($this->input->getAction(), $this);
        }
        return $view;
    }
    
    /**
     * Returns a new carousel view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param type $tmpl The template file
     * @return \Arch\View\Carousel
     */
    public function createCarousel($tmpl = null)
    {
        $view = new \Arch\View\Carousel($tmpl);
        $this->addContent(
            $this->url('/arch/asset/js/bootstrap-carousel.js'),
            'js'
        );
        return $view;
    }
    
    /**
     * Returns a new comment form.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template file path
     * @return \Arch\View\CommentForm
     */
    public function createCommentForm($tmpl = null)
    {
        return new \Arch\View\CommentForm($tmpl);
    }
    
    /**
     * Returns a new map view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template for the map
     * @return \Arch\View\Map
     */
    public function createMap($tmpl = null, \Arch\Model\Map $model = null)
    {
        $view = new \Arch\View\Map($tmpl, $model);
        $app = \Arch\App::Instance();
        $this->addContent($this->url('/arch/asset/css/leaflet.css'), 'css');
        $this->addContent(
                'http://maps.google.com/maps/api/js?v=3.2&sensor=false',
                'js'
        );
        $this->addContent($this->url('/arch/asset/js/leaflet.js'), 'js');
        $this->addContent($this->url('/arch/asset/js/leaflet.Google.js'), 'js');
        $this->addContent($this->url('/arch/asset/js/map.js'), 'js');
        return $view;
    }
    
    /**
     * Returns a new Line Chart view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The chart template file path
     * @return \Arch\View\LineChart
     */
    public function createLineChart($tmpl = null)
    {
        $view = new \Arch\View\LineChart($tmpl);
        $this->addContent($this->url('/arch/asset/css/morris.css'), 'css');
        $this->addContent($this->url('/arch/asset/js/raphael-min.js'), 'js');
        $this->addContent($this->url('/arch/asset/js/morris.js'), 'js');
        return $view;
    }
    
    /**
     * Returns a new Tree view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template for the tree
     * @return \Arch\View\TreeView
     */
    public function createTreeView($tmpl = null)
    {
        return new \Arch\View\TreeView($tmpl);
    }
    
    /**
     * Returns a new File Explorer view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $path The base path to be explored
     * @param string $tmpl The template for explorer
     * @return \Arch\View\FileExplorer
     */
    public function createFileExplorer($path, $tmpl = null)
    {
        return new \Arch\View\FileExplorer($path, $tmpl);
    }
    
    /**
     * Returns a new poll view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template for the poll
     * 
     * @return \Arch\View\Poll
     */
    public function createPoll($tmpl = null)
    {
        $view = new \Arch\View\Poll($tmpl);
        $this->addContent($this->url('/arch/asset/css/morris.css'), 'css');
        $this->addContent($this->url('/arch/asset/js/raphael-min.js'), 'js');
        $this->addContent($this->url('/arch/asset/js/morris.js'), 'js');
        return $view;
    }
    
    /**
     * Returns a new forum view.
     * 
     * You should use your own template. Copy the default template from
     * <b>vendor/taviroquai/architectphp/theme/</b> to your module directory and 
     * pass it in the <b>$tmpl</b> param.
     * 
     * @param string $tmpl The template file for the forum
     * 
     * @return \Arch\View\Forum The forum view
     */
    public function createForum($tmpl = null)
    {
        return new \Arch\View\Forum($tmpl);
    }
    
    private function addCoreRoutes()
    {
        // app alias
        $app = $this;
        
        // Add route 404! Show something if everything else fails...
        $this->router->addRoute('/404', function() use ($app) {
            $app->output->setHeaders(
                array('HTTP/1.0 404 Not Found', 'Status: 404 Not Found')
            );
            // set 404 content
            $content = '<h1>404 Not Found</h1>';
            $app->output->setContent($content);
        });
        
        // Add get static core file route
        $this->router->addRoute(
                '/arch/asset/(:any)/(:any)', 
                function($dir, $filename) use ($app) {
            $filename = ARCH_PATH.DIRECTORY_SEPARATOR.
                    'theme'.DIRECTORY_SEPARATOR.
                    'architect'.DIRECTORY_SEPARATOR.
                     $dir.DIRECTORY_SEPARATOR.$filename;
            if (!file_exists($filename)) $app->redirect ('/404');
            else {
                $app->output->readfile($filename);
                exit();
            }
        });
    }
    
    private function execute()
    {
        $input =& $this->input;
        $action = $input->getAction();
        $callback = $this->router->getRoute($input->getAction(), $input);

        if ($callback === false) {
            if ($this->router->getRoute('/404', $input) && $action != '/404') {
                $action = '/404';
                $callback = $this->router->getRoute($action, $input);
            }
        }
        
        // trigger core event
        $this->triggerEvent('arch.action.before.call', $action);
        
        $this->log('User action: '.$action);
        return call_user_func_array($callback, $input->getParam());
    }
    
    private function initDatabase()
    {
        try {
            switch (DB_DRIVER) {
                default:
                    $this->db = new \Arch\DB\MySql\Driver(
                        DB_DATABASE,
                        DB_HOST,
                        DB_USER,
                        DB_PASS,
                        $this->logger
                    );
            }
            
            // trigger core event
            $this->triggerEvent('arch.db.after.load', $this->db);
        
            $this->log('Database initialized');
        } catch (\PDOException $e)  {
            $this->addMessage (
                'A fatal database error has occured. Try later.', 
                'error'
            );
            $this->log('Database could not be initialized', 'error');
        }
    }
    
    private function loadModules()
    {
        if (!is_dir(MODULE_PATH)) {
            $this->log('Module path not found!', 'error');
            return false;
        }
        
        $modules = glob(MODULE_PATH.
                DIRECTORY_SEPARATOR.'enable'.
                DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);

        foreach($modules as $name) {
            $m_loader = $name.DIRECTORY_SEPARATOR.'src'.
                        DIRECTORY_SEPARATOR.'autoload.php';
            if (file_exists($m_loader)) {
                require_once $m_loader;
            }
            $m_config = $name.DIRECTORY_SEPARATOR.'config.php';
            if (file_exists($m_config)) {
                require_once $m_config;
            }
            $this->log('Module loaded: '.$name);
        }

        // clean up
        unset($name);
        unset($m_loader);
        unset($m_config);
        
        // save modules directories
        $this->modules = $modules;
        
        // clean up
        unset($modules);
        
        // trigger core event
        $this->triggerEvent('arch.module.after.load', $this->modules);
    }
    
    private function sendOutput()
    {
        if (is_object($this->output->getContent()) === FALSE) {
            if ($this->output->getContent() == '') {
                // trigger core event
                $this->triggerEvent('arch.theme.before.render', $this->theme);
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
        if (!$this->input->isCli()) {
            //trigger core event
            $this->triggerEvent(
                'arch.http.before.headers', 
                $this->output->getHeaders()
            );
            $this->output->sendHeaders();
        }
        //trigger core event
        $this->triggerEvent(
            'arch.output.before.send',
            $this->output->getContent()
        );
        $this->output->send();
    }
    
    private function cleanEnd()
    {
        // trigger core event
        $this->triggerEvent('arch.before.end');
        
        // close output buffer
        if (ob_get_status()) ob_end_flush();
        
        // trigger core event
        if (isset($this->session)) {
            $this->session->save();
        }
        $this->triggerEvent('arch.session.after.save');
        $this->log('Session closed');
        
        // close log handler
        $this->logger->close();
    }
}
