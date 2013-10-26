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
    
	public function __construct($tmpl = null, \Arch\Model\Map $model = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/map.php';
        }
		parent::__construct($tmpl);
        
        if ($model === null) {
            $model = new \Arch\Model\Map();
        }
        $this->model = $model;
        
        c(BASE_URL.'theme/default/leaflet/leaflet.css', 'css');
        c('http://maps.google.com/maps/api/js?v=3.2&sensor=false', 'js');
        c(BASE_URL.'theme/default/leaflet/leaflet.js', 'js');
        c(BASE_URL.'theme/default/leaflet/Google.js', 'js');
        c(BASE_URL.'theme/default/map/map.js', 'js');
	}
    
}