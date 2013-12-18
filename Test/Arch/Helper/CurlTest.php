<?php

/**
 * Description of CurlTest
 *
 * @author mafonso
 */
class CurlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test curl
     */
    public function testCreate()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Curl($app);
        $this->assertInstanceOf('\Arch\Helper\Curl', $helper);
    }
    
    /**
     * Test execute
     */
    public function testrun()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Curl($app);
        $helper->setUrl('http://fail');
        $helper->setTimeout(5);
        $helper->setData(array('param' => 'value'));
        $result = $helper->run();
        $helper->closeConnection();
        $this->assertFalse($result);
    }
}
