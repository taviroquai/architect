<?php

/**
 * Description of IdiomTest
 *
 * @author mafonso
 */
class IdiomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create idiom
     */
    public function testCreate()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Idiom($app);
        $this->assertInstanceOf('\Arch\Helper\Idiom', $helper);
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Idiom($app);
        
        $helper->setModule('test');
        $helper->setName('default.xml');
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Registry\Idiom', $result);
        
        $helper->setCode('en');
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Registry\Idiom', $result);
        
        $app = new \Arch\App();
        $app->getConfig()->set('DEFAULT_IDIOM', 'en');
        $helper = new \Arch\Helper\Idiom($app);
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Registry\Idiom', $result);
        
        $app = new \Arch\App();
        $app->getSession()->set('idiom', 'en');
        $helper = new \Arch\Helper\Idiom($app);
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Registry\Idiom', $result);
        
        $app = new \Arch\App();
        $app->getInput()->setParams(array('idiom' => 'en'));
        $helper = new \Arch\Helper\Idiom($app);
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\Registry\Idiom', $result);
    }
}
