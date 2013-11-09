<?php

/**
 * Description of ImageTest
 *
 * @author mafonso
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test invalid filename
     * @expectedException \Exception
     */
    public function testInvalidFilename()
    {
        new \Arch\Image(null);
    }
    
    /**
     * Test invalid image
     * @expectedException \Exception
     */
    public function testInvalidImage()
    {
        new \Arch\Image(RESOURCE_PATH.'/dummy');
    }
    
    /**
     * Test valid image
     */
    public function testValidImage()
    {
        new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
    }
    
    /**
     * Test fail save thumb
     */
    public function testFailSaveThumb()
    {
        $img = new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
        $result = $img->saveThumb(RESOURCE_PATH.'forbidden/thumb.jpg');
        $this->assertFalse($result);
    }
    
    /**
     * Test success save thumb
     */
    public function testSuccessSaveThumb()
    {
        $img = new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
        $result = $img->saveThumb(RESOURCE_PATH.'img/test/thumb.jpg');
        $this->assertTrue($result);
    }
    
    /**
     * Test success save portrait thumb
     */
    public function testSuccessSaveThumbPortrait()
    {
        $img = new \Arch\Image(RESOURCE_PATH.'img/portrait.jpg');
        $result = $img->saveThumb(RESOURCE_PATH.'img/test/thumb.jpg');
        $this->assertTrue($result);
    }

    /**
     * Test success save thumb with specific size
     */
    public function testSuccessSaveThumbWithSize()
    {
        $img = new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
        $result = $img->saveThumb(RESOURCE_PATH.'img/test/thumb_size.jpg', 100);
        $this->assertTrue($result);
    }
    
    /**
     * Test success create thumb
     */
    public function testSuccessSaveThumbOnDir()
    {
        $dir = RESOURCE_PATH.'img/test';
        $img = new \Arch\Image(RESOURCE_PATH.'img/landscape.jpg');
        $result = $img->saveThumb($dir);
        $this->assertTrue($result);
    }
}
