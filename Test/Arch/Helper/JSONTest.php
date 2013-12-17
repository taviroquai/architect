<?php

/**
 * Description of JSONTest
 *
 * @author mafonso
 */
class HelperJSONTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $helper = new \Arch\Helper\JSON($app = new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\JSON', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\JSON($app = new \Arch\App());
        $helper->setData(array());
        $helper->send();
    }
}
