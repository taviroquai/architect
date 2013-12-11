<?php

/**
 * Description of DirectoryTest
 *
 * @author mafonso
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail load theme from directory
     * @expectedException \Exception
     */
    public function testFailLoad()
    {
        $theme = new \Arch\Theme\Directory();
        $this->assertInstanceOf('\Arch\Theme\Directory', $theme);
        
        $theme->load('fail');
    }
    
    /**
     * Test create theme from directory
     */
    public function testLoad()
    {
        $theme = new \Arch\Theme\Directory();
        $this->assertInstanceOf('\Arch\Theme\Directory', $theme);
        
        $path = RESOURCE_PATH.'theme';
        $theme->load($path);
    }
}
