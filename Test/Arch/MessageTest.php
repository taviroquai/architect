<?php

/**
 * Description of MessageTest
 *
 * @author mafonso
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create message
     * @expectedException \Exception
     */
    public function testFailCreate()
    {
        new \Arch\Message('');
    }
    
    /**
     * Test create message
     */
    public function testCreate()
    {
        $result = new \Arch\Message('test');
        $this->assertInstanceOf('\Arch\Message', $result);
    }
}
