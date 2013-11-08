<?php

/**
 * Description of EventTest
 *
 * @author mafonso
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testInvalidEventName()
    {
        new \Arch\Event(null, null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testEmptyEventName()
    {
        new \Arch\Event('', null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidEventCallback()
    {
        new \Arch\Event('name', null);
    }
    
    /**
     * Test success trigger
     */
    public function testTrigger()
    {
        $event = new \Arch\Event('name', function(){});
        $event->trigger();
    }
}
