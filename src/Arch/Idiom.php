<?php

namespace Arch;

/**
 * Idiom class
 */
class Idiom
{	
	public $code;
	protected $storage = array();

    /**
     * Returns a new Idiom object
     * @param string $code The language code
     */
	public function __construct($code = 'en')
    {
		$this->code = $code;
        if (empty($this->code)) $this->code = 'en';
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
     * Loads idiom strings
     * @param string $filename
     * @return boolean
     */
	public function loadFile($filename)
    {
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->item as $item) {
                $key = (string) $item['key'];
                $this->storage[$key] = (string) $item;
            }
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
}
