<?php

/**
 * Description of AutoPanelTest
 *
 * @author mafonso
 */
class AutoPanelTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Data provider
     * @return array
     */
    public function providerCreate()
    {
        return array(
            array(
                '',
                array('table' => 'test_table1', 'select' => '*'),
                new \Arch\DB\MySql\Driver(
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'))
            )
        );
    }
    
    /**
     * Data provider
     * @return array
     */
    public function providerFailCreate()
    {
        return array(
            array(
                '',
                array(),
                new \Arch\DB\MySql\Driver(
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'))
            ),
            array(
                '',
                array('table' => 'test_table1'),
                new \Arch\DB\MySql\Driver(
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'))
            ),
            array(
                '',
                array('select' => ''),
                new \Arch\DB\MySql\Driver(
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'))
            )
        );
    }

    /**
     * Test fail to create AutoPanel
     * @dataProvider providerFailCreate
     * @expectedException \Exception
     */
    public function testFailCreate($tmpl, $config, $driver)
    {
        $result = new \Arch\Theme\Layout\AutoPanel();
        $result->setTemplate($tmpl);
        $result->setConfig($config);
        $result->setDatabaseDriver($driver);
        $this->assertInstanceOf('\Arch\Theme\Layout\AutoPanel', $result);
    }
    
    /**
     * Test create AutoPanel
     * @dataProvider providerCreate
     */
    public function testCreate($tmpl, $config, $driver)
    {
        $result = new \Arch\Theme\Layout\AutoPanel();
        $result->setTemplate($tmpl);
        $result->setConfig($config);
        $result->setDatabaseDriver($driver);
        $this->assertInstanceOf('\Arch\Theme\Layout\AutoPanel', $result);
    }
}
