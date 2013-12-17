<?php

namespace Arch\Registry;

/**
 * Config class
 */
class Config extends \Arch\IRegistry
{
    /**
     * Holds the required configuration keys
     * @var array
     */
    protected $required = array();
    
    /**
     * Returns a new configuration
     */
    public function __construct()
    {
        // init config storage
        $this->storage = array();
        
        // init required items
        $this->required = array(
            'BASE_URL',
            'INDEX_FILE',
            'THEME_PATH',
            'MODULE_PATH',
            'IDIOM_PATH',
            'LOG_FILE'
        );
    }
    
    /**
     * Loads configuration from a filename
     * @param string $filename The filename with xml configuration
     * @return \Arch\Config
     */
    public function load($filename)
    {    
        // load configuration
        if (!file_exists($filename)) {
            throw new \Exception('File not found');
        }
        
        $xml = @simplexml_load_file($filename);
        if (!$xml) {
            throw new \Exception('Invalid XML configuration');
        }
        
        // check for required items
        foreach ($this->required as $item) {
            $required_node = $xml->xpath('/config/item[@name="'.$item.'"]');
            if (empty($required_node)) {
                throw new \Exception('Incomplete configuration. Missing '.$item);
            }
        }
        
        foreach ($xml->item as $item) {
            $this->set((string) $item['name'], (string) $item);
        }
        
        // extra configuration
        foreach ($this->storage as $key => $value) {
            switch ($key) {
                case 'DISPLAY_ERRORS':
                    ini_set('display_errors', (int) $this->get($key)); break;
                case 'ERROR_REPORTING':
                    error_reporting((int) $this->get($key)); break;
            }
        }
        
        return $this;
    }
}