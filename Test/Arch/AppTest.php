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
        $app = new \Arch\App();
        $this->assertInstanceOf('\Arch\App', $app);
    }
    
    public function providerApp()
    {
        return array(
            array(new \Arch\App())
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
     * Test run application output theme
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testRunApplicationOutputTheme($app)
    {   
        $app->getInput()->getRouter()->addRoute('/', function() {});
        $app->getInput()->setAction('/');
        $app->getTheme()->load(RESOURCE_PATH.'theme');
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
    public function testGetGenericViewFactory($app)
    {
        $result = $app->getViewFactory();
        $this->assertInstanceOf('\Arch\Factory\GenericView', $result);
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
