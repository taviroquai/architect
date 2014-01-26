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
        // validate directory
        $this->validateDirectory($path);
        
        // clean current data
        parent::__construct();
        $this->theme_path = $path;
        
        // set required template
        $template = $path.DIRECTORY_SEPARATOR.'template.php';
        $this->setTemplate($template);
        
        // add default theme slots
        $this->slot = array();
        
        // load slots configuration
        $filename = $this->theme_path.DIRECTORY_SEPARATOR.'slots.xml';
        if (file_exists($filename)) {
            $this->loadSlots($filename);
        }
    }
    
    /**
     * Validates theme directory
     * @param string $path The theme directory
     * @throws \Exception
     */
    protected function validateDirectory($path)
    {
        if (!is_dir($path)) {
            throw new \Exception('Theme directory not found: '.$path);
        }
        
        if (!is_file($path.DIRECTORY_SEPARATOR.'template.php')) {
            throw new \Exception('Theme template not found at '.$path);
        }
    }
    
    /**
     * Loads slots configuration
     * @param string $filename The filename with slots configuration
     */
    protected function loadSlots($filename)
    {
        $xml = @simplexml_load_file($filename);
        foreach ($xml->slot as $slot) {
            $slotName = (string) $slot['name'];
            $this->addSlot($slotName);
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
}
