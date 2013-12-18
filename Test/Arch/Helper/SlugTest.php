<?php

/**
 * Description of SlugTest
 *
 * @author mafonso
 */
class SlugTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Slug($app);
        $this->assertInstanceOf('\Arch\Helper\Slug', $helper);
    }
    
    /**
     * Test execute
     */
    public function testrun()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Slug($app);
        $helper->setText('á!"#$%&/()=?ç');
        $result = $helper->run();
        $this->assertInternalType('string', $result);
        $this->assertEquals('a-c', $result);
    }
}
