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
        $app->config->set('MODULE_PATH', '');
        $app->run();
    }
}
