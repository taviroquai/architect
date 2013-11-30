<?php

namespace Arch\View;

/**
 * Map class
 */
class Map extends \Arch\View
{
    /**
     * Holds a map model
     * @var \Arch\Model\Map
     */
    public $model;
    
    /**
     * Returns a new Map view
     * @param string $tmpl The template file
     * @param \Arch\Model\Map $model The map model
     */
    public function __construct($tmpl = null, \Arch\Model\Map $model = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','map.php'));
        }
		parent::__construct($tmpl);
        
        if ($model === null) {
            $model = new \Arch\Model\Map();
        }
        $this->model = $model;
        
        $this->data['lon'] = 0;
        $this->data['lat'] = 0;
        $this->data['zoom'] = 1;
    }
    
}