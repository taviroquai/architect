<?php

/**
 * Description of MessageTest
 *
 * @author mafonso
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test invalid text
     * @expectedException \Exception
     */
    public function testInvalidText()
    {
        new \Arch\Message(null);
    }
    
    /**
     * Test empty text
     * @expectedException \Exception
     */
    public function testEmptyFilename()
    {
        new \Arch\Message('');
    }
    
    /**
     * Test success create message
     */
    public function testSuccessCreateMessage()
    {
        $message = new \Arch\Message('test');
        $this->assertInstanceOf('\Arch\Message', $message);
    }    
}
