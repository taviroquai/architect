<?php

/**
 * Description of OutputTest
 *
 * @author mafonso
 */
class OutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */   
    public function testFailCreate()
    {
        $factory = new \Arch\IFactory\OutputFactory();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\IFactory\OutputFactory();
        $factory->create(\Arch\IFactory\OutputFactory::TYPE_RAW);
        $factory->create(\Arch\IFactory\OutputFactory::TYPE_HTTP);
        $factory->create(\Arch\IFactory\OutputFactory::TYPE_RESPONSE);
        $factory->create(\Arch\IFactory\OutputFactory::TYPE_ATTACHMENT);
        $factory->create(\Arch\IFactory\OutputFactory::TYPE_JSON);
    }
}
