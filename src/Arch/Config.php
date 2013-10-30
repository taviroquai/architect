<?php

namespace Arch;

/**
 * Config class
 */
class Config
{
    
    protected $filename;
    protected $xml;
    
    /**
     * Returns a new configuration
     * @param string $filename
     */
    public function __construct($filename = null)
    {
        if (!empty($filename)) {
            $this->load ($filename);
        }
    }
    
    /**
     * Loads configuration from filename
     * @param string $filename
     */
    public function load($filename)
    {    
        // load configuration
        if (!file_exists($filename)) {
            die("The file $filename was not found.");
        }
        
        // valid filename
        $this->filename = $filename;
        $xml = @simplexml_load_file($filename);
        if (!$xml) {
            die("Invalid $filename file");
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
        
        // check path dependencies and setup defaults
        if (!defined('BASE_PATH')) {
            die('Please define BASE_PATH constant in your index.php');
        }
        if (!defined('ARCH_PATH')) {
            die('Please define ARCH_PATH constant in your index.php');
        }
        if (!defined('THEME_PATH')) {
            define('THEME_PATH', '/theme');
        }
        if (!defined('DEFAULT_THEME')) {
            define('DEFAULT_THEME', 'default');
        }
        if (!defined('DEFAULT_THEME')) {
            define('DEFAULT_THEME', 'default');
        }
        if (!defined('DEFAULT_IDIOM')) {
            define('DEFAULT_IDIOM', '/idiom');
        }
        if (!defined('LOG_PATH')) {
            define('LOG_PATH', '/log');
        }
    }
}