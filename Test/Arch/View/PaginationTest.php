<?php

/**
 * Description of PaginationTest
 *
 * @author mafonso
 */
class PaginationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Pagination();
        $this->assertInstanceOf('\Arch\View\Pagination', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\Pagination();
        $input = new \Arch\Input\HTTP\GET();
        $input->setParams(array('p'.$view->id => 1));
        $view->setInput($input);
        $view->setLimit(4);
        $view->setTotalItems(10);
        $view->parseCurrent();
        $view->getOffset();
        $view->getLimit();
        $this->assertInternalType('string', (string) $view);
        
        $view->setLimit(1);
        $view->setTotalItems(1);
        $this->assertInternalType('string', (string) $view);
    }
}
