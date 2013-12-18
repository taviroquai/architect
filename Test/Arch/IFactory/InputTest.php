<?php

/**
 * Description of InputTest
 *
 * @author mafonso
 */
class InputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */   
    public function testFailCreate()
    {
        $factory = new \Arch\IFactory\InputFactory();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\IFactory\InputFactory();
        $factory->create(\Arch\IFactory\InputFactory::TYPE_CLI);
        $factory->create(\Arch\IFactory\InputFactory::TYPE_GET);
        $factory->create(\Arch\IFactory\InputFactory::TYPE_POST);
        $input = \Arch\IFactory\InputFactory::createFromGlobals();
    }
}
