<?php

namespace Arch\View;

/**
 * Map class
 */
class Map extends \Arch\Registry\View
{
    /**
     * Holds the list of markers
     * @var array
     */
    protected $markers = array();
    
    /**
     * Returns a new Map view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','map.php'));
	parent::__construct($tmpl);
        
        $this->storage['lon'] = 0;
        $this->storage['lat'] = 0;
        $this->storage['zoom'] = 1;
    }
    
    /**
     * Returns the map markers
     * @return array
     */
    public function getMarkers()
    {
        return $this->markers;
    }

    /**
     * Adds a marker
     * @param stdClass $marker The marker; use createMarker() to create a marker
     * @return \Arch\View\Map
     */
    public function addMarker($marker)
    {
        $this->markers[] = $marker;
        return $this;
    }
    
    /**
     * Returns a new marker
     * @return stdClass
     */
    public function createMarker($lon = 0, $lat = 0, $popup = '', $open = false)
    {
        return (object) array(
            'lon' => $lon,
            'lat' => $lat,
            'popup' => $popup,
            'open' => $open
        );
    }
    
    /**
     * Returns an HTML map
     * @return string
     */
    public function __toString() {
        $this->set('markers', $this->getMarkers());
        return parent::__toString();
    }
    
}