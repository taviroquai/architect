<?php

/**
 * Description of LoggerTest
 *
 * @author mafonso
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test invalid filename
     * @expectedException \Exception
     */
    public function testInvalidFilename()
    {
        new \Arch\Logger(null);
    }
    
    /**
     * Test success create logger
     */
    public function testSuccessCreateLogger()
    {
        $logger = new \Arch\Logger(RESOURCE_PATH.'/dummy');
        $result = $logger->isOpen();
        $this->assertTrue($result);
        $logger->close();
    }
    
    /**
     * Test success log message
     */
    public function testLogMessage()
    {
        $message = 'test';
        $logger = new \Arch\Logger(RESOURCE_PATH.'/dummy');
        
        $result1 = $logger->log($message);
        $this->assertTrue($result1);
        
        $result2 = $logger->log($message, 'error', true);
        $this->assertTrue($result2);
        
        $logger->close();
    }
    
    public function testCloseLogger()
    {
        $logger = new \Arch\Logger(RESOURCE_PATH.'/dummy');
        $logger->close();
        $result = $logger->isOpen();
        $logger->close();
        $this->assertFalse($result);
    }
    
}
