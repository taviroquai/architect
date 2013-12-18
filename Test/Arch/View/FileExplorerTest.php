<?php

/**
 * Description of FileExplorerTest
 *
 * @author mafonso
 */
class FileExplorerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\FileExplorer();
        $this->assertInstanceOf('\Arch\View\FileExplorer', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\FileExplorer();
        $view->setPath(RESOURCE_PATH.'img');
        $view->setPathToUrl('');
        $this->assertInternalType('string', (string) $view);
        
        $view->setInputParam('d');
    }
}
