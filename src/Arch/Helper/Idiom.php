<?php

namespace Arch\Helper;

/**
 * Description of Idiom
 *
 * @author mafonso
 */
class Idiom extends \Arch\IHelper
{
    /**
     * Holds the idiom 2-digit code 
     * @var string
     */
    protected $code;
    
    /**
     * Holds the name of the file that holds the translations
     * @var string
     */
    protected $name;
    
    /**
     * Tells from which module the idiom file should be looked up
     * @var string
     */
    protected $module;
    
    /**
     * Returns a new idiiom helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the idiom code
     * @param string|null $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
    
    /**
     * Sets the translation file name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }
    
    /**
     * Sets the module that holds the trabslation file
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = (string) $module;
    }

    /**
     * Returns a new idiom used to translate strings
     * @return \Arch\Registry\Idiom
     */
    public function run() {
        
        // resolve idiom code
        $current_code   = $this->code;
        $input_code     = $this->app->getInput()->get('idiom');
        $session_code   = $this->app->getSession()->get('idiom');
        $config_code    = $this->app->getConfig()->get('DEFAULT_IDIOM');
        $default_code   = 'en';
        $list = array(
            $current_code,
            $input_code,
            $session_code,
            $config_code,
            $default_code
        );
        $this->code = current(array_filter($list));
        if (!$this->app->getSession()->get('idiom')) {
            $this->app->getSession()->set('idiom', $this->code);
        }
        $idiom = new \Arch\Registry\Idiom($this->code);
        $filename = $idiom->resolveFilename(
            $this->name,
            $this->module,
            $this->app->getConfig()->get('IDIOM_PATH'),
            $this->app->getConfig()->get('MODULE_PATH')
        );
        if (!$idiom->loadTranslation($filename)) {
            $this->app->getLogger()->log('Translation failed: '.$filename);
        }
        
        // trigger core event
        $this->app->getEvents()->triggerEvent('arch.idiom.after.load', $this);
        return $idiom;
    }
}
