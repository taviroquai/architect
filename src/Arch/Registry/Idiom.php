<?php

namespace Arch\Registry;

/**
 * Idiom class
 */
class Idiom extends \Arch\IRegistry
{	
    
    /**
     * Holds the idiom ISO code
     * @var string
     */
    protected $code;
    
    /**
     * Holds the translation strings
     * @var array
     */
    protected $storage = array();

    /**
     * Returns a new Idiom object
     * @param string $code The language code
     */
    public function __construct($code = 'en')
    {
        if (!is_string($code) || empty($code)) {
            throw new \Exception('Invalid ISO code');
        }
        $this->code = $code;
    }
    
    /**
     * Sets the language code
     * @param string $code The language code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @param string $filename The path of the translation file
     * @return boolean
     */
    public function loadTranslation($filename)
    {
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            if ($xml) {
                foreach ($xml->item as $item) {
                    $key = (string) $item['key'];
                    $this->storage[$key] = (string) $item;
                }
                return true;
            }
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
     * Resolves translation filename
     * @param string $name The requested translation name
     * @param string $module The requested module name
     * @return string
     */
    public function resolveFilename(
            $name,
            $module = 'app',
            $idiom_path = '/idiom',
            $module_path = '/module'
    )
    {
        if ($module == 'app') {
            $filename = $idiom_path.DIRECTORY_SEPARATOR.$this->code.
                    DIRECTORY_SEPARATOR.$name;
        } else {
            $filename = $module_path.DIRECTORY_SEPARATOR.'enable'.
                    DIRECTORY_SEPARATOR.$module.
                    DIRECTORY_SEPARATOR.'idiom'.
                    DIRECTORY_SEPARATOR.$this->code.
                    DIRECTORY_SEPARATOR.$name;
        }
        return $filename;
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
