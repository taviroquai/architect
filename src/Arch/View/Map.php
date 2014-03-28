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
        
        $this->setCenter();
    }
    
    /**
     * Sets the map center coordinates
     * @param int $longitude The longitude coordinate
     * @param int $latitude The latitude coordinate
     * @param int $zoom The zoom level (or Z coordinate)
     */
    public function setCenter($longitude = 0, $latitude = 0, $zoom = 1)
    {
        $this->set('lon', $longitude);
        $this->set('lat', $latitude);
        $this->set('zoom', $zoom);
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