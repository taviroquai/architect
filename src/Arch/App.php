<?php

namespace Arch;

/**
 * Define Architect base path
 */
if (!defined('ARCH_PATH')) {
    define('ARCH_PATH', realpath(__DIR__ . '/../../'));
}

/**
 * Application API
 */
class App
{
    /**
     * Holds the application default configuration
     * @var \Arch\Registry\Config
     */
    private $config;
    
    /**
     * Holds user input.
     * 
     * To return a param use <b>app()->input->get('param')</b> or 
     * <b>g('param')</b>.
     * 
     * To return all params use <b>app()->input->get()</b> or <b>g()</b>.
     * 
     * To return a FILES entry use <b>app()->input->getFileByIndex($index)</b> or 
     * <b>f($index)</b>.
     * 
     * To return raw input use <b>app()->input->getRaw()</b>
     * 
     * @var \Arch\Input The application input object
     */
    private  $input;
    
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
    private  $output;
    
    /**
     * Holds the URI Router.
     * 
     * To add a route use <b>app()->addRoute('/demo', function() { ... })</b> or
     * <b>r('/demo', function() { ... })</b>.
     * 
     * The function callback will be called when the user requests
     * <b>index.php/demo</b>
     * 
     * @var \Arch\Registry\Router The URI router
     */
    private  $router;
    
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
    private  $theme;
    
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
     * @var \Arch\Registry\Session
     */
    private  $session;
    
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
    private  $db;
    
    /**
     * Holds the application logger
     * @var \Arch\Logger
     */
    private  $logger;
    
    /**
     * Holds loaded modules
     * @var \Arch\Registry\Modules
     */
    private  $modules;
    
    /**
     * Holds global application events
     * @var \Arch\Events\Registry
     */
    private  $events;
    
    /**
     * Holds the generic views factory
     * @var \Arch\IFactory\GenericViewsFactory
     */
    private  $viewsFactory;
    
    /**
     * Holds the helper factory
     * @var \Arch\IFactory\HelperFactory
     */
    private  $helperFactory;
    
    /**
     * Holds the current running stage
     * @var string
     */
    private  $stage = '';

    /**
     * Returns a new application
     * @param string $filename Full configuration file path
     */
    public function __construct($filename = 'config.xml')
    {   
        // update stage
        $this->stage = 'init';
        
        // load configuration and apply
        $this->config = new \Arch\Registry\Config();
        $this->config->load($filename);
        $this->config->apply();
        
        // ready to start logging now
        $this->logger = new \Arch\Logger($this->config->get('LOG_FILE'));
        $this->logger->log('Loaded configuration from '.$filename, 'access', true);
        
        // set events registry
        $this->events = new \Arch\Registry\Events();
        
        // set session handler
        $this->session = new \Arch\Registry\Session\Native();

        // set default theme
        $this->theme = new \Arch\Theme\Directory();
        
        // set input
        $input_factory = new \Arch\IFactory\InputFactory();
        $this->input = $input_factory->createFromGlobals();
        $this->input->parseAction($this->config);
        $this->logger->log('Input finish loading: '.
                $this->input->getUserAgent());

        // set default Output
        $output_factory = new \Arch\IFactory\OutputFactory();
        $this->output = $output_factory->createFromGlobals();
        
        // set default routes
        $this->router = new \Arch\Registry\Router();
        $this->router->addCoreRoutes($this);
        
        // set modules registry
        $this->modules = new \Arch\Registry\Modules();
        
        // create the generic views factory
        $this->viewsFactory = new \Arch\IFactory\GenericViewFactory($this);
        
        // create the helper factory
        $this->helperFactory = new \Arch\IFactory\HelperFactory($this);
    }
    
    /**
     * Runs the application through various stages.
     * 
     * It can only be called once.
     * @throws \Exception
     */
    public function run()
    {
        // prevent infinit calls
        if ($this->stage === 'run') {
            throw new \Exception('The application is already running');
        }
        
        // update stage
        $this->stage = 'run';
        
        // bypass user modules if it is a core action (arch)
        // main purpose is to improve performance
        if (!$this->input->isArchAction()) {
            $this->loadModules();
        }
        
        // trigger core event
        $this->getEvents()->triggerEvent('arch.database.load');
        $this->logger->log('Default database loaded');

        // trigger core event
        $this->getEvents()->triggerEvent('arch.session.load');
        $this->logger->log('Default session loaded');
        
        // trigger core event
        $this->getEvents()->triggerEvent('arch.theme.load');
        $this->logger->log('Default theme loaded');

        // execute action
        $this->dispatch();

        // send output
        $this->sendOutput();

        // close resources
        $this->cleanEnd();
        
        // trigger core event
        $this->getEvents()->triggerEvent('arch.before.end');
    }
    
    /**
     * Returns the application logger
     * @return \Arch\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Returns the configuration registry
     * @return \Arch\Registry\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Returns the events registry
     * @return \Arch\Registry\Events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Returns the default database handler
     * @return \Arch\DB\IDriver
     */
    public function getDatabase()
    {
        return $this->db;
    }
    
    /**
     * Returns the session handler
     * @return \Arch\Registry\ISession
     */
    public function getSession()
    {
        return $this->session;
    }
    
    /**
     * Returns the user input handler
     * @return \Arch\IInput
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Returns the default theme handler
     * @return \Arch\ITheme
     */
    public function getTheme()
    {
        return $this->theme;
    }
    
    /**
     * Returns the router registry
     * @return \Arch\Registry\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Returns the default output handler
     * @return \Arch\IOutput
     */
    public function getOutput()
    {
        return $this->output;
    }
    
    /**
     * Returns the views factory
     * @return \Arch\IFactory\GenericViewsFactory
     */
    public function getViewFactory()
    {
        return $this->viewsFactory;
    }
    
    /**
     * Returns the helper factory
     * @return \Arch\IFactory\HelperFactory
     */
    public function getHelperFactory()
    {
        return $this->helperFactory;
    }

    /**
     * Overrides the current session handler
     * @param \Arch\Registry\ISession $session
     */
    public function setSession(\Arch\ISession $session)
    {
        $this->session = $session;
    }
    
    /**
     * Sets the default output handler
     * @param \Arch\IOutput $output
     */
    public function setOutput(\Arch\IOutput $output)
    {
        $this->output = $output;
    }
    
    /**
     * Sets the default database driver
     * @param \Arch\DB\IDriver $db
     */
    public function setDatabase(\Arch\DB\IDriver $db)
    {
        $this->db = $db;
    }

    /**
     * Returns session messages.
     * 
     * @return array
     */
    public function flushMessages()
    {
        $messages = $this->session->getMessages();
        $this->session->clearMessages();
        return $messages;
    }
    
    /**
     * Initiates default database driver from user configuration
     * @throws \Exception
     */
    public function initDatabase()
    {
        try {
            $factory = new \Arch\IFactory\DatabaseFactory();
            $this->setDatabase($factory->createFromConfig(
                $this->config, $this->logger
            ));
            
            // trigger core event
            $this->getEvents()->triggerEvent('arch.db.after.load', $this->db);
        
            $this->logger->log('Database initialized');
            
        } catch (\PDOException $e)  {
            $this->getSession()->createMessage (
                'A fatal database error has occured. Try later.', 
                'alert alert-error'
            );
            $this->logger->log('Database could not be initialized', 'error');
            throw new \Exception('Could not initialize DB: '.$e->getMessage());
        }
    }
    
    private function cleanEnd()
    {
        $this->getEvents()->triggerEvent('arch.session.save');
        $this->logger->log('Session closed');
        
        // close log handler
        $this->logger->close();
    }
    
    private function dispatch()
    {
        $action = $this->input->getAction();
        $callback = $this->router->getRouteCallback($this->input);
        
        // trigger core event
        $this->getEvents()->triggerEvent('arch.action.before.call', $action);
        
        $this->logger->log('User action: '.$action);
        return call_user_func_array($callback, $this->input->getActionParam());
    }
    
    private function loadModules()
    {
        $module_path = $this->config->get('MODULE_PATH');
        if (!is_dir($module_path)) {
            $this->logger->log('Module path not found!', 'error');
            return false;
        }
        
        $this->modules->load($module_path);
        
        // trigger core event
        $this->getEvents()->triggerEvent(
            'arch.module.after.load',
            $this->modules
        );
    }
    
    private function sendOutput()
    {
        if (is_object($this->output->getBuffer()) === FALSE) {
            if ($this->output->getBuffer() == '') {
                // trigger core event
                $this->getEvents()->triggerEvent(
                    'arch.theme.before.render',
                    $this->theme
                );
                $this->output->setBuffer($this->theme);
            }
        }
        
        // send output
        $this->logger->log('Sending output...');
        //trigger core event
        $this->getEvents()->triggerEvent(
            'arch.output.before.send',
            $this->output
        );
        $this->output->send();
    }
}
