<?php

/**
 * Description of OutputTest
 *
 * @author mafonso
 */
class OutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for read file
     * @return array
     */
    public function providerReadFile()
    {
        return array(
            array(RESOURCE_PATH.'dummy'),
            array(RESOURCE_PATH.'css/dummy.css'),
            array(RESOURCE_PATH.'font/dummy.eot'),
            array(RESOURCE_PATH.'font/dummy.otf'),
            array(RESOURCE_PATH.'font/dummy.ttf'),
            array(RESOURCE_PATH.'font/dummy.woff'),
            array(RESOURCE_PATH.'img/dummy.svg'),
            array(RESOURCE_PATH.'img/landscape.jpg'),
            array(RESOURCE_PATH.'js/dummy.js'),
        );
    }
    
    /**
     * Test create output
     */
    public function testCreateOutput()
    {
        new \Arch\Output();
    }
    
    /**
     * Test get content
     */
    public function testGetContent()
    {
        $expected = 'test';
        $output = new \Arch\Output($expected);
        $result = $output->getContent();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test set content
     */
    public function testSetContent()
    {
        $expected = 'test';
        $output = new \Arch\Output();
        $output->setContent($expected);
        $result = $output->getContent();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test set and get headers
     */
    public function testSetGetHeaders()
    {
        $expected = array('1', '2', '3');
        $output = new \Arch\Output();
        $output->setHeaders($expected);
        $result = $output->getHeaders();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test send output
     */
    public function testSend()
    {
        $expected = 'test';
        $output = new \Arch\Output($expected);
        ob_start();
        $output->send();
        $result = ob_get_clean();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test read static file
     * param string $filename The filename to be read
     * dataProvider providerReadFile
     * runInSeparateProcess
     */
    /*
    public function testReadFile($filename)
    { 
        $expected = file_get_contents($filename);
        $output = new \Arch\Output();
        ob_start();
        $output->readfile($filename);
        $result = ob_get_clean();
        $this->assertEquals($expected, $result);
    }
     */
}
