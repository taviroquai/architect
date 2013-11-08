<?php

namespace Arch;

/**
 * Config class
 */
class Config
{
    /**
     * Holds the configuration filename
     * @var string
     */
    protected $filename;
    
    /**
     * Holds the original xml configuration
     * @var \SimpleXMLElement
     */
    protected $xml;
    
    protected $required;
    
    /**
     * Returns a new configuration
     */
    public function __construct()
    {
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
        
        // valid filename
        $this->filename = $filename;
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
        
        // valid xml file
        $this->xml = $xml;
        return $this;
    }
    
    /**
     * Apply configuration
     * @return \Arch\Config
     */
    public function apply()
    {
        // setup required items
        $node = $this->xml->xpath('/config/item[@name="BASE_URL"]');
        define('BASE_URL', (string) $node[0]);
        $node = $this->xml->xpath('/config/item[@name="INDEX_FILE"]');
        define('INDEX_FILE', (string) $node[0]);
        $node = $this->xml->xpath('/config/item[@name="THEME_PATH"]');
        define('THEME_PATH', (string) $node[0]);
        $node = $this->xml->xpath('/config/item[@name="MODULE_PATH"]');
        define('MODULE_PATH', (string) $node[0]);
        $node = $this->xml->xpath('/config/item[@name="IDIOM_PATH"]');
        define('IDIOM_PATH', (string) $node[0]);
        $node = $this->xml->xpath('/config/item[@name="LOG_FILE"]');
        define('LOG_FILE', (string) $node[0]);
        
        // extra configuration
        foreach ($this->xml->item as $item) {
            $name = (string) $item['name'];
            switch ($name) {
                case 'DISPLAY_ERRORS':
                    ini_set('display_errors', (int) $item); break;
                case 'ERROR_REPORTING':
                    error_reporting((int) $item); break;
                default:
                    if (!defined($name)) {
                        define($name, (string) $item);
                    }
            }
        }
        
        return $this;
    }
}