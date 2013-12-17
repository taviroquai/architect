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
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Image($app);
        $this->assertInstanceOf('\Arch\Helper\Image', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Image($app);
        $helper->setFilename(RESOURCE_PATH.'img/landscape.jpg');
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Image', $result);
    }
}
