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
        $helper = new \Arch\Helper\Slug(new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\Slug', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\Slug(new \Arch\App());
        $helper->setText('á!"#$%&/()=?ç');
        $result = $helper->execute();
        $this->assertInternalType('string', $result);
        $this->assertEquals('a-c', $result);
    }
}
