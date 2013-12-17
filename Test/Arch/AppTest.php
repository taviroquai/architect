<?php

/**
 * Description of AppTest
 *
 * @author mafonso
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create application
     */
    public function testCreateApplication()
    {
        $config = RESOURCE_PATH.'/configValid.xml';
        $app = new \Arch\App($config);
        $this->assertInstanceOf('\Arch\App', $app);
    }
    
    public function providerApp()
    {
        return array(
            array(new Arch\App(RESOURCE_PATH.'/configValid.xml'))
        );
    }

    /**
     * Test run application
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testRunApplication($app)
    {
        $app->run();
        $app->run();
    }
    
    /**
     * Test run application no modules
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testRunApplicationNoModules($app)
    {   
        $app->getConfig()->set('MODULE_PATH', '');
        $app->run();
    }
    
    /**
     * Test get logger
     * @dataProvider providerApp
     * @param \Arch\App $app
     */
    public function testGetLogger($app)
    {
        $result = $app->getLogger();
        $this->assertInstanceOf('\Arch\ILogger', $result);
    }
    
    /**
     * Test get views factory
     * @dataProvider providerApp
     * @param \Arch\App $app
     */
    public function testGetViewsFactory($app)
    {
        $result = $app->getViewFactory();
        $this->assertInstanceOf('\Arch\IFactory\GenericViewFactory', $result);
    }
    
    /**
     * Test set/get database
     * @dataProvider providerApp
     * @param \Arch\App $app
     */
    public function testSetGetDatabase($app)
    {
        $app->setDatabase(new \Arch\DB\MySql\Driver());
        $result = $app->getDatabase();
        $this->assertInstanceOf('\Arch\DB\IDriver', $result);
    }
    
    /**
     * Test flush messages
     * @dataProvider providerApp
     * @param \Arch\App $app
     */
    public function testFlushMessages($app)
    {
        $result = $app->flushMessages();
        $this->assertInternalType('array', $result);
    }
}
