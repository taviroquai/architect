<?php

/**
 * Description of MenuTest
 *
 * @author mafonso
 */
class MenuTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Menu();
        $this->assertInstanceOf('\Arch\View\Menu', $view);
    }
    
    /**
     * Test setting default css class
     */
    public function testSetCssClass()
    {
        $expected = 'test';
        $view = new \Arch\View\Menu();
        $view->setCssClass($expected);
        $this->assertEquals($expected, $view->get('cssClass'));
    }

    /**
     * Test add item
     */
    public function testAddItem()
    {
        $view = new \Arch\View\Menu();
        $view->addItem('test', '#');
        $this->assertInternalType('string', (string) $view);
    }
}
