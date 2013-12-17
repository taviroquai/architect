<?php

/**
 * Description of QueryTest
 *
 * @author mafonso
 */
class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Query($app);
        $this->assertInstanceOf('\Arch\Helper\Query', $helper);
    }
    
    /**
     * Test fail execute
     * @expectedException \Exception
     */
    public function testFailExecute()
    {
        $app = new \Arch\App();
        $helper = new \Arch\Helper\Query($app);
        $helper->setTablename('test_table1');
        $helper->execute();
    }
    
    /**
     * Test fail execute
     */
    public function testExecute()
    {
        $app = new \Arch\App();
        $app->setDatabase(new \Arch\DB\MySql\Driver());
        $helper = new \Arch\Helper\Query($app);
        $helper->setTablename('test_table1');
        $result = $helper->execute();
        $this->assertInstanceOf('\Arch\DB\ITable', $result);
    }
}
