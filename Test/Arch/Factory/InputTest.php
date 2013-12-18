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
        $factory = new \Arch\Factory\Input();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\Factory\Input();
        $factory->create(\Arch::TYPE_INPUT_CLI);
        $factory->create(\Arch::TYPE_INPUT_GET);
        $factory->create(\Arch::TYPE_INPUT_POST);
        $input = \Arch\Factory\Input::createFromGlobals();
    }
}
