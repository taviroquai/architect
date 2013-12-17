<?php

/**
 * Description of DownloadTest
 *
 * @author mafonso
 */
class DownloadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test download attachment
     */
    public function testCreate()
    {
        $helper = new \Arch\Helper\Download($app = new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\Download', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\Download($app = new \Arch\App());
        
        $helper->setFilename(RESOURCE_PATH.'fail');
        $result = $helper->execute();
        $this->assertFalse($result);
        
        $helper->setFilename(RESOURCE_PATH.'dummy');
        $helper->asAttachment(true);
        $result = $helper->execute();
        $this->assertTrue($result);
    }
}
