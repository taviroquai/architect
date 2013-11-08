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
    
    /**
     * Returns a new configuration
     */
    public function __construct()
    {
        
    }
    
    /**
     * Loads configuration from a filename
     * @param string $filename
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
        
        // valid xml file
        $this->xml = $xml;
    }
    
    /**
     * Apply configuration
     */
    public function apply()
    {
        foreach ($this->xml->item as $item) {
            $name = (string) $item['name'];
            if ($name === 'DISPLAY_ERRORS') {
                ini_set('display_errors', (int) $item);
            }
            if ($name === 'ERROR_REPORTING') {
                error_reporting((int) $item);
            }
            define($name, (string) $item);
        }
        
        // setup defaults
        if (!defined('THEME_PATH')) {
            define('THEME_PATH', '/theme');
        }
        if (!defined('CACHE_PATH')) {
            define('CACHE_PATH', '/cache');
        }
        if (!defined('DEFAULT_IDIOM')) {
            define('DEFAULT_IDIOM', '/idiom');
        }
        if (!defined('LOG_FILE')) {
            define('LOG_FILE', '/log');
        }
    }
}