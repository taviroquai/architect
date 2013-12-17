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
        $helper = new \Arch\Helper\Curl($app = new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\Curl', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\Curl($app = new \Arch\App());
        $helper->setUrl('http://localhost');
        $helper->setTimeout(5);
        $helper->setData(array('param' => 'value'));
        $result = $helper->execute();
        $helper->closeConnection();
        $this->assertInternalType('string', $result);
    }
}
