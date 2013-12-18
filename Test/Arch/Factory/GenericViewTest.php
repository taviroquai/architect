<?php

/**
 * Description of GenericViewTest
 *
 * @author mafonso
 */
class GenericViewTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */   
    public function testFailCreate()
    {
        $app = new \Arch\App(RESOURCE_PATH.'configValid.xml');
        $factory = new \Arch\Factory\GenericView($app);
        $factory->create(99);
    }
    
    public function providerTestCreate()
    {
        return array(
            array('AntiSpam'),
            array('AutoForm'),
            array('AutoTable'),
            array('Breadcrumbs'),
            array('Carousel'),
            array('CommentForm'),
            array('DatePicker'),
            array('FileExplorer'),
            array('FileUpload'),
            array('ImageGallery'),
            array('LineChart'),
            array('Map'),
            array('Menu'),
            array('Pagination'),
            array('Poll'),
            array('TextEditor'),
            array('TreeView')
        );
    }

    /**
     * Test create
     * @dataProvider providerTestCreate
     */
    public function testCreate($name)
    {
        $app = new \Arch\App(RESOURCE_PATH.'configValid.xml');
        $factory = new \Arch\Factory\GenericView($app);
        $factory->create($name);
    }
}
