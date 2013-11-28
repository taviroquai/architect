<?php

/**
 * Description of IdiomTest
 *
 * @author mafonso
 */
class IdiomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test invalid idiom code
     * @expectedException \Exception
     */
    public function testInvalidIdiomCode()
    {
        new \Arch\Idiom(null);
    }
    
    /**
     * Test empty idiom code
     * @expectedException \Exception
     */
    public function testEmptyIdiomCode()
    {
        new \Arch\Idiom('');
    }
    
    /**
     * Test set code
     */
    public function testSetCode()
    {
        $expected = 'pt';
        $idiom = new \Arch\Idiom('en');
        $idiom->setCode($expected);
        $result = $idiom->getCode();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test get code
     */
    public function testGetCode()
    {
        $expected = 'pt';
        $idiom = new \Arch\Idiom($expected);
        $result = $idiom->getCode();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test fail load translation
     */
    public function testFailLoadTranslation()
    {
        $idiom = new \Arch\Idiom('en');
        $result = $idiom->loadTranslation('');
        $this->assertFalse($result);
    }
    
    /**
     * Test fail load translation
     */
    public function testInvalidLoadTranslation()
    {
        $idiom = new \Arch\Idiom('en');
        $filename = RESOURCE_PATH.'/idiom/en/defaultInvalid.xml';
        $result = $idiom->loadTranslation($filename);
        $this->assertFalse($result);
    }
    
    /**
     * Test fail load translation
     */
    public function testSuccessLoadTranslation()
    {
        $idiom = new \Arch\Idiom('en');
        $filename = RESOURCE_PATH.'/idiom/en/default.xml';
        $result = $idiom->loadTranslation($filename);
        $this->assertTrue($result);
    }
    
    /**
     * Test resolve app filename translation
     */
    public function testResolveAppTranslation()
    {
        // default values
        if (!defined('IDIOM_PATH')) define('IDIOM_PATH', '/idiom');
        if (!defined('MODULE_PATH')) define('MODULE_PATH', '/module');

        $expected = '/idiom/en/default.xml';
        $idiom = new \Arch\Idiom('en');
        $result = $idiom->resolveFilename('default.xml');
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test resolve module filename translation
     */
    public function testResolveModuleTranslation()
    {
        $module = 'test';
        $expected = '/module/enable/'.$module.'/idiom/en/default.xml';
        $idiom = new \Arch\Idiom('en');
        $result = $idiom->resolveFilename('default.xml', $module);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test not found translation
     */
    public function testNotFoundTranslate()
    {
        $expected = 'NOTFOUND';
        $idiom = new \Arch\Idiom('en');
        $idiom->loadTranslation(RESOURCE_PATH.'/idiom/en/default.xml');
        $result = $idiom->translate($expected);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test translation
     */
    public function testTranslate()
    {
        $expected = 'TESTVALUE';
        $idiom = new \Arch\Idiom('en');
        $idiom->loadTranslation(RESOURCE_PATH.'/idiom/en/default.xml');
        $result1 = $idiom->translate('TESTKEY');
        $this->assertEquals($expected, $result1);
        $result2 = $idiom->t('TESTKEY');
        $this->assertEquals($expected, $result2);
    }
    
    /**
     * Test translation with data substitution
     */
    public function testTranslateData()
    {
        $data = array('DATA');
        $expected = 'TEST DATA VALUE';
        $idiom = new \Arch\Idiom('en');
        $idiom->loadTranslation(RESOURCE_PATH.'/idiom/en/default.xml');
        $result = $idiom->translate('TESTKEYDATA', $data);
        $this->assertEquals($expected, $result);
    }
}
