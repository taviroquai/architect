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
    public function testExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Curl($app);
        $helper->setUrl('http://localhost');
        $helper->setTimeout(5);
        $helper->setData(array('param' => 'value'));
        $result = $helper->execute();
        $helper->closeConnection();
        $this->assertInternalType('string', $result);
    }
}
