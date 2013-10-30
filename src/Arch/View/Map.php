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
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','map.php'));
        }
		parent::__construct($tmpl);
        
        if ($model === null) {
            $model = new \Arch\Model\Map();
        }
        $this->model = $model;
        
        $app = \Arch\App::Instance();
        $app->addContent($app->url('/arch/asset/css/leaflet.css'), 'css');
        $app->addContent(
                'http://maps.google.com/maps/api/js?v=3.2&sensor=false',
                'js'
        );
        $app->addContent($app->url('/arch/asset/js/leaflet.js'), 'js');
        $app->addContent($app->url('/arch/asset/js/leaflet.Google.js'), 'js');
        $app->addContent($app->url('/arch/asset/js/map.js'), 'js');
	}
    
}