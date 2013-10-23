<?php

namespace Arch;

/**
 * Idiom class
 */
class Idiom
{	
	public $code;
	protected $storage = array();

	public function __construct($code = 'en')
    {
		$this->code = $code;
        if (empty($this->code)) $this->code = 'en';
	}

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

	public function translate($key, $data = array())
    {
		if (empty($this->storage[$key])) {
            return $key;
        }
		if (!empty($data)) {
            return @vsprintf($this->storage[$key], $data);
        }
		return $this->storage[$key];
    }
}
