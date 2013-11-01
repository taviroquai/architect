<?php

namespace Arch;

/**
 * Theme class
 */
class Theme extends \Arch\View
{
    
    protected $theme_path;
    protected $app;

    /**
     * Constructor
     * 
     * @param string $path
     */
    public function __construct($path, \Arch\App $app)
    {
        parent::__construct();
        
        if (!is_dir($path)) die('Default theme not found: '.$path);
        $this->theme_path = $path;
        $this->app = $app;
        
        // add default theme slots
        $this->addSlot('css');
        $this->addSlot('js');
        
        // add theme configuration
        $filename = $this->theme_path.DIRECTORY_SEPARATOR.'config.php';
        if (file_exists($filename)) {
            require_once $filename;
        }
    }
    
    public function __toString()
    {
        $filename = $this->theme_path.DIRECTORY_SEPARATOR.'slots.xml';
        if (file_exists($filename)) {
            $xml = @simplexml_load_file($filename);
            foreach ($xml->slot as $slot) {
                $slotName = (string) $slot['name'];
                foreach ($slot->module as $item) {
                    $classname = (string) $item->classname;
                    if (!class_exists($classname)) {
                        continue;
                    }
                    $c = isset($item->content) ? (string) $item->content : '';
                    $module = new $classname($c);
                    $this->addContent($module, $slotName);
                }
            }
        }
        
        // trigger core event
        $this->app->triggerEvent('arch.theme.before.render', $this);
        
        return parent::__toString();
    }
}
