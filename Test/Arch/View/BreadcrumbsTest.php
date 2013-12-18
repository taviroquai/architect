<?php

/**
 * Description of BreadcrumbsTest
 *
 * @author mafonso
 */
class BreadcrumbsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Breadcrumbs();
        $this->assertInstanceOf('\Arch\View\Breadcrumbs', $view);
    }
    
    /**
     * Test parse action
     */
    public function testParseAction()
    {
        $view = new \Arch\View\Breadcrumbs();
        $view->parseAction(new \Arch\App());
    }
    
    /**
     * Test add item
     */
    public function testAddItem()
    {
        $view = new \Arch\View\Breadcrumbs();
        $view->addItem('test');
        $this->assertInternalType('string', (string) $view);
    }
}
