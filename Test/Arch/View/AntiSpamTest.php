<?php

/**
 * Description of AntiSpamTest
 *
 * @author mafonso
 */
class AntiSpamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\AntiSpam();
        $this->assertInstanceOf('\Arch\View\AntiSpam', $view);
    }
    
    /**
     * Test fail session validate
     * @expectedException \Exception
     */
    public function testFailSessionValidate()
    {
        $view = new \Arch\View\AntiSpam();
        $view->validate();
    }
    
    /**
     * Test fail input validate
     * @expectedException \Exception
     */
    public function testFailInputValidate()
    {
        $view = new \Arch\View\AntiSpam();
        $view->setSession(new \Arch\Registry\Session\File());
        $view->validate();
    }
    
    /**
     * Test validate
     */
    public function testValidate()
    {
        $view = new \Arch\View\AntiSpam();
        $view->setSession(new \Arch\Registry\Session\File());
        $view->setInput(new \Arch\Input\CLI());
        $view->validate();
    }
}
