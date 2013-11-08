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
        $item->load(RESOURCE_PATH.'configInvalid.xml');
    }
    
    /**
     * @expectedException \Exception
     */
    public function testIncompleteConfig()
    {
        $item = new \Arch\Config();
        $item->load(RESOURCE_PATH.'configIncomplete.xml');
    }
    
    /**
     * Test success load configuration
     */
    public function testValidConfig()
    {
        $config = new \Arch\Config();
        $result = $config->load(RESOURCE_PATH.'configValid.xml');
        $this->assertInstanceOf('\Arch\Config', $result);
    }
    
    /**
     * Test success apply configuration
     */
    public function testApplyConfig()
    {
        $config = new \Arch\Config();
        $config->load(RESOURCE_PATH.'configValid.xml');
        $config->apply();
        $this->assertTrue(defined('BASE_URL'));
        $this->assertTrue(defined('INDEX_FILE'));
        $this->assertTrue(defined('LOG_FILE'));
        $this->assertTrue(defined('MODULE_PATH'));
        $this->assertTrue(defined('THEME_PATH'));
        $this->assertTrue(defined('IDIOM_PATH'));
    }
}
