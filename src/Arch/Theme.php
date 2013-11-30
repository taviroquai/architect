<?php

namespace Arch;

/**
 * Theme class
 */
class Theme extends \Arch\View
{
    /**
     * Holds the theme directory path
     * @var string
     */
    protected $theme_path;

    /**
     * Constructor
     * 
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct();
        
        if (!is_dir($path)) {
            throw new \Exception('Default theme not found: '.$path);
        }
        $this->theme_path = $path;
        
        // add default theme slots
        $this->addSlot('css');
        $this->addSlot('js');
        
        // load slots configuration
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
        
        // clean up
        unset($filename);
        unset($xml);
        unset($slot);
        unset($slotName);
        unset($item);
        unset($classname);
        unset($c);
        unset($module);
        
        // add theme configuration
        $filename = $this->theme_path.DIRECTORY_SEPARATOR.'config.php';
        if (file_exists($filename)) {
            require $filename;
        }
    }
}
