<?php

/**
 * Description of JSONTest
 *
 * @author mafonso
 */
class JSONTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create JSON output
     */
    public function testCreate()
    {
        $result = new \Arch\Output\HTTP\Response\JSON();
        $this->assertInstanceOf('\Arch\Output\HTTP\Response\JSON', $result);
    }
    
    /**
     * Test headers
     */
    public function testBuffer()
    {
        $output = new \Arch\Output\HTTP\Response\JSON();
        $expected = (object) array('property' => 'value');
        $output->setBuffer(json_encode($expected));
        $result = json_decode($output->getBuffer());
        $this->assertEquals($expected, $result);
    }
    
}
