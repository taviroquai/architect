<?php

/**
 * Description of ConfigTest
 *
 * @author mafonso
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testConfigFileNotFound()
    {
        $item = new \Arch\Config();
        $item->load('config.xml');
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidConfig()
    {
        $item = new \Arch\Config();
        $item->load(__DIR__.DIRECTORY_SEPARATOR.'configInvalid.xml');
    }
    
    /**
     * Test success load configuration
     */
    public function testValidConfig()
    {
        $config = new \Arch\Config();
        $result = $config->load(__DIR__.DIRECTORY_SEPARATOR.'configValid.xml');
        $this->assertInstanceOf('\Arch\Config', $result);
    }
}
