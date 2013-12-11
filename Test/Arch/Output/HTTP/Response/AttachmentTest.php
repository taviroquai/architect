<?php

/**
 * Description of AttachmentTest
 *
 * @author mafonso
 */
class AttachmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create attachement output
     */
    public function testCreate()
    {
        $result = new \Arch\Output\HTTP\Response\Attachment();
        $this->assertInstanceOf('\Arch\Output\HTTP\Response\Attachment', $result);
    }
    
    /**
     * Test headers
     */
    public function testBuffer()
    {
        $output = new \Arch\Output\HTTP\Response\Attachment();
        
        $filename = RESOURCE_PATH.'dummy';
        $expected = file_get_contents($filename);
        $output->import($filename);
        $result = $output->getBuffer();
        $this->assertEquals($expected, $result);
        $output->addCacheHeaders();
        $output->getHeaders();
    }
    
}
