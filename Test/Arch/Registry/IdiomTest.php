<?php

/**
 * Description of RegistryIdiomTest
 *
 * @author mafonso
 */
class RegistryIdiomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */
    public function testFailCreate()
    {
        new \Arch\Registry\Idiom('');
    }
    
    /**
     * Test create
     */
    public function testCreate()
    {
        $idiom = new \Arch\Registry\Idiom();
        $expected = 'pt';
        $idiom->setCode($expected);
        $result = $idiom->getCode();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test resolve idiom filename
     */
    public function testResolveIdiomFile()
    {
        $idiom = new \Arch\Registry\Idiom();
        $result = $idiom->resolveFilename('default.xml');
        $expected = './idiom/en/default.xml';
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test load translation
     */
    public function testLoadTranslation()
    {
        $idiom = new \Arch\Registry\Idiom();
        $idiom->loadTranslation(RESOURCE_PATH.'idiom/en/default.xml');
        
        $result = $idiom->t('TESTKEY');
        $expected = 'TESTVALUE';
        $this->assertEquals($expected, $result);
        
        $result = $idiom->t('TESTKEYDATA', array('param'));
        $expected = 'TEST param VALUE';
        $this->assertEquals($expected, $result);
    }
}
