<?php

namespace Arch\View;

/**
 * FileExplorer class
 */
class FileExplorer extends \Arch\View
{

    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/filelist.php';
        }
        parent::__construct($tmpl);
        
        // init items
        $this->data['base'] = BASE_PATH;
        $this->data['param'] = 'd';
        $this->data['url'] = '/';
    }
    
    public function __toString()
    {
        $path = g($this->data['param']) ? 
            $this->data['base'].g($this->data['param']) : 
            $this->data['base'];
        $path = rtrim($path, '/');
        if (!file_exists($path))  return '';
        $this->set('path', $path);
        
        $parent = str_replace($this->data['base'], '', dirname($path));
        $this->set('parent', $parent == '' ? '/' : $parent);
        $items = glob($path."/*", GLOB_ONLYDIR);
        $items = array_merge($items, array_filter(glob($path."/*"), 'is_file'));
        $this->set('files', $items);
        return parent::__toString();
    }
    
}
