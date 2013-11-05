<?php

namespace Arch;

/**
 * Idiom class
 */
class Idiom
{	

    protected $app;
    protected $code;
	protected $storage = array();

    /**
     * Returns a new Idiom object
     * @param string $code The language code
     */
	public function __construct(\Arch\App &$app, $filename = 'default.xml')
    {
        $this->app = $app;
        if ($this->app->input->get('idiom')) {
            $this->setCode($this->app->input->get('idiom'));
        } else {
            if (defined('DEFAULT_IDIOM')) {
                $this->setCode(DEFAULT_IDIOM, true);
            } else {
                $this->setCode('en', true);
            }
        }
        $this->loadtranslation($filename);
        
        // trigger core event
        $this->app->triggerEvent('arch.idiom.after.load', $this);
	}
    
    /**
     * Sets the language code
     * @param string $code The language code
     * @param boolean $session Tells whether should be set to all session or not
     */
    public function setCode($code, $session = false)
    {
        $this->code = $code;
        if ($session) $this->app->session->idiom = $code;
    }


    /**
     * Returns the language code
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Loads a translation file
     * 
     * Translation files are plain xml (no programming skills needed)
     * 
     * Example:
     * $idiom->loadTranslation('/path/to/file.xml');
     * 
     * To call an idiom string use: t('KEY')
     * 
     * @param string $filename The basename of the translation file
     * @param string $module The name of the module
     * @return \Arch\App
     */
    public function loadTranslation($filename, $module = 'app')
    {
        if ($module == 'app') {
            $filename = IDIOM_PATH.DIRECTORY_SEPARATOR.$this->code.
                    DIRECTORY_SEPARATOR.$filename;
        } else {
            $filename = MODULE_PATH.DIRECTORY_SEPARATOR.$module.
                    DIRECTORY_SEPARATOR.'idiom'.
                    DIRECTORY_SEPARATOR.$this->code.
                    DIRECTORY_SEPARATOR.$filename;
        }
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->item as $item) {
                $key = (string) $item['key'];
                $this->storage[$key] = (string) $item;
            }
            $this->app->log('Idiom file loaded: '.$filename);
            return true;
        }
        return false;
	}

    /**
     * Converts a string key to the idiom string
     * @param string $key
     * @param array $data
     * @return string
     */
	public function translate($key, $data = array())
    {
		if (empty($this->storage[$key])) {
            return $key;
        }
		if (!empty($data)) {
            return @vsprintf($this->storage[$key], $data);
        }
		return (string) $this->storage[$key];
    }
    
    /**
     * Translate alias
     * Converts a string key to the idiom string
     * @param string $key The translation key
     * @param array $data Data to be used in translation
     * @return string
     */
	public function t($key, $data = array())
    {
        return $this->translate($key, $data);
    }
}
