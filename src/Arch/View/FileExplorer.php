<?php

namespace Arch\View;

/**
 * FileExplorer class
 */
class FileExplorer extends \Arch\View
{
    /**
     * Holds the callback to translate server file path to client url
     * @var function
     */
    protected $path_to_url;
    
    /**
     * Holds the param input
     * @var string
     */
    protected $input_param;

    /**
     * Returns a new file explorer
     * @param string $path The local path
     * @param string $tmpl The template file
     */
    public function __construct($path, $tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','filelist.php'));
        }
        parent::__construct($tmpl);
        
        // init items
        $this->data['base'] = is_file($path) ? dirname($path) : $path;
        $this->data['param'] = 'd';
        $this->data['url'] = '/';
        
        // init function to translate path to url
        $this->path_to_url = function ($path) {
            return $path;
        };
    }
    
    /**
     * Allows to redefine file path to url translation
     * @param function $fn
     * @return \Arch\View\FileExplorer
     */
    public function setPathToUrl($fn)
    {
        if (is_callable($fn)) {
            $this->path_to_url = $fn;
        }
        return $this;
    }
    
    /**
     * Translates file path to url
     * @param string $path The file path
     * @return string
     */
    public function translatePath($path) {
        $fn = $this->path_to_url;
        return $fn($path);
    }
    
    /**
     * Sets the input param
     * @param string $value
     */
    public function setInputParam($value)
    {
        $this->input_param = $value;
    }


    /**
     * Resolves current path based on $_GET
     * @return string
     */
    public function getPath()
    {
        $path = $this->input_param ? 
            $this->data['base'].$this->input_param : 
            $this->data['base'];
        return rtrim($path, '/');
    }
    
    /**
     * Returns only the list of folders
     * @return array
     */
    public function getFolders()
    {
        return glob($this->getPath()."/*", GLOB_ONLYDIR);
    }
    
    /**
     * returns only the list of files
     * @return array
     */
    public function getFiles()
    {
        return array_filter(glob($this->getPath()."/*"), 'is_file');
    }
    
    /**
     * Renders the file explorer view
     * @return string
     */
    public function __toString()
    {
        $path = $this->getPath();
        if (!file_exists($path))  return '';
        $this->set('path', $path);
        
        $parent = str_replace($this->data['base'], '', dirname($path));
        $this->set('parent', $parent == '' ? '/' : $parent);
        $folders = $this->getFolders();
        $files = $this->getFiles();
        $items = array_merge($folders, $files);
        $this->set('files', $items);
        return parent::__toString();
    }
    
}
