<?php

/**
 * Description of database TableTest
 *
 * @author mafonso
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provider of PDO connection
     * @return array
     */
    public function providerConnection()
    {
        return array(
            array(
                new \Arch\DB\MySql\Driver(
                    DB_DATABASE,
                    DB_HOST,
                    DB_USER,
                    DB_PASS,
                    new \Arch\Logger(RESOURCE_PATH.'dummy')
                )
            )
        );
    }
    
    /**
     * Provider for testing PDO param value
     * @return array
     */
    public function providerConnectionInsertInvalidValue()
    {
        return array(
            array(
                new \Arch\DB\MySql\Driver(
                    DB_DATABASE,
                    DB_HOST,
                    DB_USER,
                    DB_PASS,
                    new \Arch\Logger(RESOURCE_PATH.'dummy')
                ),
                null
            ),
            array(
                new \Arch\DB\MySql\Driver(
                    DB_DATABASE,
                    DB_HOST,
                    DB_USER,
                    DB_PASS,
                    new \Arch\Logger(RESOURCE_PATH.'dummy')
                ),
                array()
            ),
            array(
                new \Arch\DB\MySql\Driver(
                    DB_DATABASE,
                    DB_HOST,
                    DB_USER,
                    DB_PASS,
                    new \Arch\Logger(RESOURCE_PATH.'dummy')
                ),
                new stdClass()
            ),
            array(
                new \Arch\DB\MySql\Driver(
                    DB_DATABASE,
                    DB_HOST,
                    DB_USER,
                    DB_PASS,
                    new \Arch\Logger(RESOURCE_PATH.'dummy')
                ),
                function() { }
            )
        );
    }

    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testInvalidTable($driver)
    {
        $result = new \Arch\DB\MySql\Table(null, $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testEmptyTable($driver)
    {
        $result = new \Arch\DB\MySql\Table('', $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testCreateTable($driver)
    {
        $result = new \Arch\DB\MySql\Table('test_table1', $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test fail install
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailInstall($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->install(RESOURCE_PATH.'not_found');
        $this->assertFalse($result);
        $result = $table->install(RESOURCE_PATH.'db/fail.sql');
        $this->assertFalse($result);
    }
    
    /**
     * Test install
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testInstall($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->install(RESOURCE_PATH.'db/install.sql');
        $this->assertTrue($result);
    }
    
    /**
     * Test fail execute statement
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatement($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->run('select from');
        $this->assertFalse($result);
    }

    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailRun($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelect($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailSelect($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select with where
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectWhere($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail where
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailSelectWhere($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and wrong number of params
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailSelectWhereParams($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field1 = ?', array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and params
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectWhereParams($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field1 = ?', array(1))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test select and join
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectJoin($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_nmrelation', $driver);
        $result = $table->s()->j(
                'test_table1',
                'test_table1.id = test_nmrelation.id_table1'
                )->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test select and join
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectJoinAuto($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_nmrelation', $driver);
        $result = $table->s()->joinAuto()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and group
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailSelectGroup($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->g('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and group
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectGroup($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->g('id')->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and limit
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailSelectLimit($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->l('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and limit
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectLimit($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->l(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail insert
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailInsert($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test insert
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testInsert($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array('field1' => 'insert'))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $id = $table->getInsertId();
        $this->assertNotEmpty($id);
        
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array('field1' => false))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $id = $table->getInsertId();
        $this->assertNotEmpty($id);
    }
    
    /**
     * Test fail update
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailUpdate($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->u(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test update
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testUpdate($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->u(array('field1' => 'update'))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $rows = $table->getRowCount();
        $this->assertInternalType('integer', $rows);
    }
    
    /**
     * Test select fetch first
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectFetch($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetch();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test select fetch all
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectFetchAll($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchAll();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test select fetch object
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectFetchObject($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchObject();
        $this->assertInstanceOf('\stdClass', $result);
    }
    
    /**
     * Test select fetch column
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testSelectFetchColumn($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchColumn();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test delete
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testDelete($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_nmrelation', $driver);
        $result = $table->d()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $rows = $table->getRowCount();
        $this->assertInternalType('integer', $rows);
    }
    
    /**
     * Test invalid statement on select fetch
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementSelectFetch($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetch();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test invalid statement on select fetch all
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementSelectFetchAll($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchAll();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test invalid statement on select fetch object
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementSelectFetchObject($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchObject();
        $this->assertFalse($result);
    }
    
    /**
     * Test invalid statement on select fetch column
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementSelectFetchColumn($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchColumn();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test invalid statement on insert
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementInsert($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array('field1' => 'insert'))
                ->where('field1 = ?', array(new stdClass))
                ->getInsertId();
        $this->assertFalse($result);
    }
    
    /**
     * Test invalid statement on update
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     */
    public function testFailStatementUpdate($driver)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->u(array('field1' => 'update'))
                ->where('field1 = ?', array(new stdClass))
                ->getRowCount();
        $this->assertFalse($result);
    }
    
    /**
     * Test insert with invalid values
     * @dataProvider providerConnectionInsertInvalidValue
     * @param \Arch\DB\Driver $driver
     * @param mixed $value The value to be tested
     */
    public function testInsertInvalidValue($driver, $value)
    {
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array('field1' => $value))->getInsertId();
        $this->assertFalse($result);
    }

}
