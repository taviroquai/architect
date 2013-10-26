<?php

namespace Arch\Model;

/**
 * Model Map
 */
class Map
{

    protected $markers = array();
    
    /**
     * Returns a new map model
     */
    public function __construct()
    {
        
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
}