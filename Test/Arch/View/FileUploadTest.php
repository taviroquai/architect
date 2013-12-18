<?php

/**
 * Description of FileUploadTest
 *
 * @author mafonso
 */
class FileUploadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\FileUpload();
        $this->assertInstanceOf('\Arch\View\FileUpload', $view);
    }
    
    /**
     * Test upload
     */
    public function testUpload()
    {
        $view = new \Arch\View\FileUpload();
        $file = array(
            'tmp_name' => RESOURCE_PATH.'/dummy',
            'name' => 'dummy'
        );
        $view->upload($file, RESOURCE_PATH);
    }
}
