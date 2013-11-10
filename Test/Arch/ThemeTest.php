<?php

/**
 * Description of ThemeTest
 *
 * @author mafonso
 */
class ThemeTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Test fail create theme
     * @expectedException \Exception
     */
    public function testFailCreate()
    {
        new \Arch\Theme(RESOURCE_PATH.'dummy');
    }
    
    /**
     * Test create theme
     */
    public function testCreate()
    {
        $path = RESOURCE_PATH.'theme';
        $theme = new \Arch\Theme($path);
        $this->assertInstanceOf('\Arch\Theme', $theme);
        $this->assertInternalType('string', (string) $theme);
        $this->assertTrue($theme->get('config'));
    }
}
