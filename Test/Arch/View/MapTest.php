<?php

/**
 * Description of MapTest
 *
 * @author mafonso
 */
class MapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Map();
        $this->assertInstanceOf('\Arch\View\Map', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\Map();
        $marker = $view->createMarker();
        $view->addMarker($marker);
        $markers = $view->getMarkers();
        $this->assertInternalType('string', (string) $view);
    }
}
