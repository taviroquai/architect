<?php

namespace Arch\Helper;

/**
 * Description of CreateIdiom
 *
 * @author mafonso
 */
class CreateIdiom extends \Arch\Helper
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

    public function execute() {
        
        // resolve idiom code
        if (empty($this->code) && $this->app->getInput()->get('idiom')) {
            $this->code = $this->app->getInput()->get('idiom');
        }
        if (empty($this->code) && $this->app->getSession()->get('idiom')) {
            $this->code = $this->app->getSession()->get('idiom');
        }
        if (empty($this->code) && $this->app->getConfig()->get('DEFAULT_IDIOM')) {
            $this->code = $this->app->getConfig()->get('DEFAULT_IDIOM');
        }
        if (empty($this->code)) {
            $this->code = 'en';
        }
        if (!$this->app->getSession()->get('idiom')) {
            $this->app->getSession()->set('idiom', $this->code);
        }
        $idiom = new \Arch\Idiom($this->code);
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
