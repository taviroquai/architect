<?php

/**
 * Description of HelperTest
 *
 * @author mafonso
 */
class HelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */   
    public function testFailCreate()
    {
        $app = new \Arch\App(RESOURCE_PATH.'configValid.xml');
        $factory = new \Arch\Factory\Helper($app);
        $factory->create(99);
    }
    
    public function providerTestCreate()
    {
        return array(
            array('Curl'),
            array('Download'),
            array('Idiom'),
            array('Image'),
            array('JSON'),
            array('Query'),
            array('Redirect'),
            array('Slug'),
            array('URL'),
            array('Validator')
        );
    }

        /**
     * Test create
     * @dataProvider providerTestCreate
     */
    public function testCreate($name)
    {
        $app = new \Arch\App(RESOURCE_PATH.'configValid.xml');
        $factory = new \Arch\Factory\Helper($app);
        $factory->create($name);
    }
    
    public function testFactoryMethod()
    {
        $app = new \Arch\App(RESOURCE_PATH.'configValid.xml');
        $factory = new \Arch\Factory\Helper($app);
        
        $factory->createCurl('http://localhost');
        $factory->createDownload(RESOURCE_PATH.'dummy');
        $factory->createIdiom();
        $factory->createImage(RESOURCE_PATH.'dummy');
        $factory->createJSON(array());
        $factory->createQuery('test_table1');
        $factory->createRedirect();
        $factory->createSlug('test');
        $factory->createURL();
        $factory->createValidator();
    }
}
