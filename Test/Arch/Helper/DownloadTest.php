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
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Download($app);
        $this->assertInstanceOf('\Arch\Helper\Download', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Download($app);
        
        $helper->setFilename(RESOURCE_PATH.'fail');
        $result = $helper->execute();
        $this->assertFalse($result);
        
        $helper->setFilename(RESOURCE_PATH.'dummy');
        $helper->asAttachment(true);
        $result = $helper->execute();
        $this->assertTrue($result);
    }
}
