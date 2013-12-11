<?php

/**
 * Description of LayoutTest
 *
 * @author mafonso
 */
class LayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create layout
     */
    public function testCreate()
    {
        $layout = new \Arch\Theme\Layout();
        $this->assertInstanceOf('\Arch\Theme\Layout', $layout);
        $this->assertNotEmpty($layout->id);
        
        $expected = array();
        $result = $layout->getSlots();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test render slot
     */
    public function testRender()
    {
        $layout = new \Arch\Theme\Layout();
        $this->assertInstanceOf('\Arch\Theme\Layout', $layout);
        $this->assertNotEmpty($layout->id);
        
        $layout->addContent('test');
        $result = $layout->render('content', function($item) {});
        $this->assertInstanceOf('\Arch\Theme\Layout', $result);
    }
    
    /**
     * Test add unique content
     */
    public function testAddUniqueContent()
    {
        $layout = new \Arch\Theme\Layout();
        
        $layout->addContent('test1', 'js');
        $layout->addContent('test1', 'js');
        
        $expected = array('test1');
        $result = $layout->getSlotItems('js');
        $this->assertEquals($expected, $result);
    }
    
    public function testEmptySlot()
    {
        $layout = new \Arch\Theme\Layout();
        $layout->addContent('test1');
        
        $expected = array();
        $layout->emptySlot();
        $result = $layout->getSlotItems();
        $this->assertEquals($expected, $result);
    }
}
