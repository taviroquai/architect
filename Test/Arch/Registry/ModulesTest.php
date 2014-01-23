<?php

/**
 * Description of ModulesTest
 *
 * @author mafonso
 */
class ModulesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test modules registry
     */
    public function testCreate()
    {
        $registry = new \Arch\Registry\Modules();
        $this->assertInstanceOf('\Arch\Registry\Modules', $registry);
    }
    
    /**
     * Test load module
     */
    public function testLoadModule()
    {
        $registry = new \Arch\Registry\Modules();
        $this->assertInstanceOf('\Arch\Registry\Modules', $registry);
        
        $result = $registry->load(RESOURCE_PATH.'fail');
        $this->assertFalse($result);
        
        $result = $registry->load(RESOURCE_PATH.'module');
        $this->assertTrue($result);
    }
}
