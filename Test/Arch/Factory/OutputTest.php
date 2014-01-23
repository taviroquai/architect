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
        $factory = new \Arch\Factory\Output();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\Factory\Output();
        $factory->create(\Arch::TYPE_OUTPUT_RAW);
        $factory->create(\Arch::TYPE_OUTPUT_HTTP);
        $factory->create(\Arch::TYPE_OUTPUT_RESPONSE);
        $factory->create(\Arch::TYPE_OUTPUT_ATTACHMENT);
        $factory->create(\Arch::TYPE_OUTPUT_JSON);
        \Arch\Factory\Output::createFromGlobals();
    }
}
