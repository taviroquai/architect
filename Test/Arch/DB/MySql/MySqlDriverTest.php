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
                new \Arch\DB\MySql\Driver(),
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy')
            )
        );
    }

    /**
     * Test create MySql driver
     */
    public function testCreate()
    {
        $result = new \Arch\DB\MySql\Driver();
        $this->assertInstanceOf('\Arch\DB\MySql\Driver', $result);
    }
    
    /**
     * Test connect
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testConnect($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getPDO();
        $this->assertInstanceOf('\PDO', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testCreateTable($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->createTable('test_table1');
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testGetTables($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getTables();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get table info
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testGetTableInfo($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getTableInfo('test_table1');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test fail get table info
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testFailGetTableInfo($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getTableInfo('test_table');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get table foreign keys
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testGetTableForeignKeys($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getForeignKeys('test_nmrelation', 'id_table1');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test fail get table foreign keys
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testFailGetTableForeignKeys($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getForeignKeys('test_nmrelatio', 'id_table1');
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test get relation column between two tables
     * @dataProvider providerDriver
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     */
    public function testGetRelationColumn($driver, $database, $host, $user, $pass)
    {
        $driver->connect($host, $database, $user, $pass);
        $result = $driver->getRelationColumn('test_nmrelation', 'test_table1');
        $this->assertInternalType('string', $result);
    }
}
