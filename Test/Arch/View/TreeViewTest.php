<?php

/**
 * Description of TreeViewTest
 *
 * @author mafonso
 */
class TreeViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\TreeView();
        $this->assertInstanceOf('\Arch\View\TreeView', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\TreeView();
        $view->createNode('attribute', 'value');
        $this->assertInternalType('string', (string) $view);
    }
}
