<?php

/**
 * Description of ViewTest
 *
 * @author mafonso
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create view
     */
    public function testCreate()
    {
        $view = new \Arch\Registry\View();
        $this->assertInstanceOf('\Arch\Registry\View', $view);
        $this->assertNotEmpty($view->id);
        
        $view = new \Arch\Registry\View(RESOURCE_PATH.'/template/div.php');
        $this->assertInstanceOf('\Arch\Registry\View', $view);
    }
    
    /**
     * Test render
     */
    public function testToString()
    {
        $view = new \Arch\Registry\View(RESOURCE_PATH.'/template/div.php');
        $result = (string) $view;
        $this->assertInternalType('string', $result);
        
        $view->hide();
        $result = (string) $view;
        $this->assertEmpty($result);
        $view->show();
        
        $view->setTemplate('fail');
        $result = (string) $view;
        $this->assertInternalType('string', $result);
    }
    
}
