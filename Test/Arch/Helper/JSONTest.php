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
        $app = new \Arch\App();
        $helper = new \Arch\Helper\JSON($app);
        $this->assertInstanceOf('\Arch\Helper\JSON', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\JSON($app);
        $helper->setData(array());
        $helper->send();
    }
}
