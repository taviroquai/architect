<?php

/**
 * Description of ImageTest
 *
 * @author mafonso
 */
class ArchImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create image
     * @expectedException \Exception
     */
    public function testFailCreate()
    {
        new \Arch\Image(NULL);
    }
    
    /**
     * Test fail create image
     * @expectedException \Exception
     */
    public function testFailCreateNoImage()
    {
        new \Arch\Image(RESOURCE_PATH.'dummy');
    }
    
    /**
     * Test create message
     */
    public function testCreate()
    {
        $result = new \Arch\Image(RESOURCE_PATH.'img/portrait.jpg');
        $this->assertInstanceOf('\Arch\Image', $result);
    }
    
    /**
     * Test create thumb
     */
    public function testSaveThumb()
    {
        $image = new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
        $image->saveThumb(RESOURCE_PATH.'img/test');
        $image->saveThumb(RESOURCE_PATH.'img/test/thumb.jpg');
        $image->saveThumb(RESOURCE_PATH.'img/test/thumb_size.jpg', 50);
        
        $image->saveThumb(RESOURCE_PATH.'img/test/thumb.png');
        $image->saveThumb(RESOURCE_PATH.'img/test/thumb.gif');
    }
}
