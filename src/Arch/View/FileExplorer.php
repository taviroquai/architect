<?php

namespace Arch\View;

/**
 * FileExplorer class
 */
class FileExplorer extends \Arch\Registry\View
{
    /**
     * The local file path to be explored
     * @var string
     */
    protected $path;
    
    /**
     * Holds the callback to translate server file path to client url
     * @var string
     */
    protected $path_to_url;
    
    /**
     * Holds the param input
     * @var string
     */
    protected $input_param;

    /**
     * Returns a new file explorer
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','filelist.php'));
        parent::__construct($tmpl);
        
        // init function to translate path to url
        $this->path_to_url = '';
    }
    
    public function setPath($path)
    {
        $this->path = $path;
        // init items
        $this->storage['base'] = is_file($path) ? dirname($path) : $path;
        $this->storage['param'] = 'd';
        $this->storage['url'] = '/';
    }

    /**
     * Allows to redefine file path to url translation
     * @param string $url
     * @return \Arch\View\FileExplorer
     */
    public function setPathToUrl($url)
    {
        $this->path_to_url = $url;
        return $this;
    }
    
    /**
     * Translates file path to url
     * @return string
     */
    public function translatePath()
    {
        return $this->path_to_url;
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
            $this->storage['base'].$this->input_param : 
            $this->storage['base'];
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
        
        $parent = str_replace($this->storage['base'], '', dirname($path));
        $this->set('parent', $parent == '' ? '/' : $parent);
        $backlink = $this->get('url') . '&'
            . $this->get('param') . '=' . $this->get('parent');
        $this->set('backlink', $backlink);
        $folders = $this->getFolders();
        $files = $this->getFiles();
        $items = array_merge($folders, $files);
        $this->set('files', $items);
        return parent::__toString();
    }
    
}
