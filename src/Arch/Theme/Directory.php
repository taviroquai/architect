<?php

namespace Arch\Theme;

/**
 * Theme class
 */
class Directory extends \Arch\ITheme
{
    /**
     * Holds the theme directory path
     * @var string
     */
    protected $theme_path;
    
    /**
     * Loas a theme directory
     * @param string $path The theme directory
     * @throws \Exception
     */
    public function load($path)
    {
        if (!is_dir($path)) {
            throw new \Exception('Theme directory not found: '.$path);
        }
        
        // clean current data
        parent::__construct();
        $this->theme_path = $path;
        
        // add default theme slots
        $this->slot = array();
        $this->addSlot('content');
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
            include $filename;
        }
        
        // add flash messages slot
        $this->set(
            'messages',
            new \Arch\Registry\View($this->theme_path.DIRECTORY_SEPARATOR.'messages.php')
        );
    }
}
