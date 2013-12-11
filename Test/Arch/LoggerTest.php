<?php

/**
 * Description of LoggerTest
 *
 * @author mafonso
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create logger
     * @expectedException \Exception
     */
    public function testFailCreate()
    {
        new \Arch\Logger(null);
    }
    
    /**
     * Test create message
     */
    public function testCreate()
    {
        $logger = new \Arch\Logger('test');
        $this->assertInstanceOf('\Arch\Logger', $logger);
        
        $result = $logger->log('');
        $this->assertFalse($result);
    }
    
    public function testLog()
    {
        $logger = new \Arch\Logger(RESOURCE_PATH.'/dummy');
        $result = $logger->log('test');
        $this->assertTrue($result);
    }
}
