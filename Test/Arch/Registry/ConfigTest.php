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
        $item = new \Arch\Registry\Config();
        $item->load('config.xml');
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidConfig()
    {
        $item = new \Arch\Registry\Config();
        $item->load(RESOURCE_PATH.'configInvalid.xml');
    }
    
    /**
     * @expectedException \Exception
     */
    public function testIncompleteConfig()
    {
        $item = new \Arch\Registry\Config();
        $item->load(RESOURCE_PATH.'configIncomplete.xml');
    }
    
    /**
     * Test success load configuration
     */
    public function testValidConfig()
    {
        $config = new \Arch\Registry\Config();
        $result = $config->load(RESOURCE_PATH.'configValid.xml');
        $this->assertInstanceOf('\Arch\Registry\Config', $result);
    }
    
    /**
     * Test success apply configuration
     */
    public function testApplyConfig()
    {
        $config = new \Arch\Registry\Config();
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
