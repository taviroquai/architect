<?php

/**
 * Description of EventsTest
 *
 * @author mafonso
 */
class EventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail add event
     * @expectedException \Exception
     */
    public function testFailEvents()
    {
        $events = new \Arch\Registry\Events();
        $events->addEvent('', function() {});
    }
    
    /**
     * Test events
     */
    public function testEvents()
    {
        $events = new \Arch\Registry\Events();
        $events->addEvent('test', function() {});
        $events->triggerEvent('test');
    }
}
