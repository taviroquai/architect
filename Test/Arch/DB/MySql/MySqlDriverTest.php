<?php

/**
 * Description of database MysqlDriverTest
 *
 * @author mafonso
 */
class MySqlDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider
     * @return array
     */
    public function providerDriver()
    {
        return array(
            array(
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
     * Test create MySql driver
     */
    public function testCreate()
    {
        $result = new \Arch\DB\MySql\Driver(
            DB_DATABASE,
            DB_HOST,
            DB_USER,
            DB_PASS,
            new \Arch\Logger(RESOURCE_PATH.'dummy')
        );
        $this->assertInstanceOf('\Arch\DB\MySql\Driver', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerDriver
     * @param \Arch\DB\Driver $driver
     */
    public function testCreateTable($driver)
    {
        $result = $driver->createTable('test_table1');
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerDriver
     * @param \Arch\DB\Driver $driver
     */
    public function testGetTables($driver)
    {
        $result = $driver->getTables();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get table info
     * @dataProvider providerDriver
     * @param \Arch\DB\Driver $driver
     */
    public function testGetTableInfo($driver)
    {
        $result = $driver->getTableInfo('test_table1');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get table foreign keys
     * @dataProvider providerDriver
     * @param \Arch\DB\Driver $driver
     */
    public function testGetTableForeignKeys($driver)
    {
        $result = $driver->getForeignKeys('test_nmrelation', 'id_table1');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get relation column between two tables
     * @dataProvider providerDriver
     * @param \Arch\DB\Driver $driver
     */
    public function testGetRelationColumn($driver)
    {
        $result = $driver->getRelationColumn('test_nmrelation', 'test_table1');
        $this->assertInternalType('string', $result);
    }
}
