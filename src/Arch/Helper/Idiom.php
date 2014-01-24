<?php

namespace Arch\Helper;

/**
 * Description of Idiom
 *
 * @author mafonso
 */
class Idiom extends \Arch\IHelper
{
    protected $code;
    protected $name;
    protected $module;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setCode($code)
    {
        $this->code = $code;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setModule($module)
    {
        $this->module = $module;
    }

    public function run() {
        
        // resolve idiom code
        $input_code     = $this->app->getInput()->get('idiom');
        $session_code   = $this->app->getSession()->get('idiom');
        $current_code   = $this->code;
        $config_code    = $this->app->getConfig()->get('DEFAULT_IDIOM');
        $default_code   = 'en';
        $list = array(
            $input_code,
            $session_code,
            $current_code,
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
