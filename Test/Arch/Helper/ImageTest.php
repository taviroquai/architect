<?php

/**
 * Description of ImageTest
 *
 * @author mafonso
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $helper = new \Arch\Helper\Image(new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\Image', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\Image(new \Arch\App());
        $helper->setFilename(RESOURCE_PATH.'img/landscape.jpg');
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Image', $result);
    }
}
