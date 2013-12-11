<?php

/**
 * Description of RawTest
 *
 * @author mafonso
 */
class RawTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create raw output
     */
    public function testCreate()
    {
        $result = new \Arch\Output\Raw();
        $this->assertInstanceOf('\Arch\Output\Raw', $result);
    }
    
    /**
     * Test headers
     */
    public function testHeaders()
    {
        $output = new \Arch\Output\Raw();
        
        $headers = array();
        $output->setHeaders($headers);
        
        $output->addHeader('test');
        
        $result = $output->getHeaders();
        $this->assertEmpty($result);
    }
    
    /**
     * Test import content from file
     */
    public function testImport()
    {
        $filename = RESOURCE_PATH.'/dummy';
        $expected = file_get_contents($filename);
        $output = new \Arch\Output\Raw('test');
        
        $result = $output->import($filename);
        $this->assertTrue($result);
        
        $result = $output->getBuffer();
        $this->assertEquals($expected, $result);
        
        $result = $output->import('error');
        $this->assertFalse($result);
    }
}
