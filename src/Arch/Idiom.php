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
		if (App::Instance()->input->get('idiom')) {
			$this->session->_idiom = App::Instance()->input->get('idiom');
			$this->code = $this->session->_idiom;
		}
		if (App::Instance()->session->_idiom == null) {
			App::Instance()->session->_idiom = $this->code;
        }
	}

	public function loadFile($filename)
    {
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->item as $item) {
                $key = (string) $item['key'];
                $this->storage[$key] = (string) $item;
            }
            \Arch\App::Instance()->log('Idiom file loaded: '.$filename);
        }
        else {
            \Arch\App::Instance()->log(
                'Idiom file load failed: '.$filename,
                'error'
            );
        }
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
