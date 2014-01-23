<?php

/**
 * Description of FileTest
 *
 * @author mafonso
 */
class LoggerFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create message
     */
    public function testCreate()
    {
        $logger = new \Arch\Logger\File();
        $this->assertInstanceOf('\Arch\Logger\File', $logger);
    }
    
    /**
     * Tests log
     */
    public function testLog()
    {
        $logger = new \Arch\Logger\File();
        $logger->log('test');
        $this->assertInstanceOf('\Arch\ILogger', $logger);
    }
    
    /**
     * Test fail set filename
     * @expectedException \Exception
     */
    public function testFailSetFilename()
    {
        $logger = new \Arch\Logger\File();
        $logger->setFilename(NULL);
    }
    
    /**
     * Test dump log
     */
    public function testDumpLog()
    {
        $logger = new \Arch\Logger\File();
        $logger->setFilename(RESOURCE_PATH.'/dummy');
        $logger->log('test');
        $logger->dumpMessages();
        $logger->close();
    }
}
