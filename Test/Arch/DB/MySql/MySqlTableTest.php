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
     * Provider for testing PDO param value
     * @return array
     */
    public function providerConnectionInsertInvalidValue()
    {
        return array(
            array(
                new \Arch\DB\MySql\Driver(),
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                null
            ),
            array(
                new \Arch\DB\MySql\Driver(),
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                array()
            ),
            array(
                new \Arch\DB\MySql\Driver(),
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                new stdClass()
            ),
            array(
                new \Arch\DB\MySql\Driver(),
                DB_DATABASE,
                DB_HOST,
                DB_USER,
                DB_PASS,
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                function() { }
            )
        );
    }

    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \Arch\DB\IDriver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testInvalidTable($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $result = new \Arch\DB\MySql\Table(null, $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testEmptyTable($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $result = new \Arch\DB\MySql\Table('', $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testCreateTable($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $result = new \Arch\DB\MySql\Table('test_table1', $driver);
        $this->assertInstanceOf('\Arch\DB\MySql\Table', $result);
    }
    
    /**
     * Test fail install
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailInstall($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testInstall($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->install(RESOURCE_PATH.'db/install.sql');
        $this->assertTrue($result);
    }
    
    /**
     * Test fail execute statement
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatement($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->run('select from');
        $this->assertFalse($result);
    }

    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailRun($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelect($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailSelect($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select with where
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectWhere($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail where
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailSelectWhere($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and wrong number of params
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailSelectWhereParams($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field1 = ?', array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and params
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectWhereParams($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->w('field1 = ?', array(1))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test select and join
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectJoin($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectJoinAuto($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_nmrelation', $driver);
        $result = $table->s()->joinAuto()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and group
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailSelectGroup($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->g('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and group
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectGroup($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->g('id')->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and limit
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailSelectLimit($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->l('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and limit
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectLimit($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->l(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail insert
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailInsert($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test insert
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testInsert($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailUpdate($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->u(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test update
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testUpdate($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectFetch($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetch();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test select fetch all
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchAll($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchAll();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test select fetch object
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchObject($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchObject();
        $this->assertInstanceOf('\stdClass', $result);
    }
    
    /**
     * Test select fetch column
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchColumn($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->s()->fetchColumn();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test delete
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testDelete($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_dummy', $driver);
        $result = $table->d()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $rows = $table->getRowCount();
        $this->assertInternalType('integer', $rows);
    }
    
    /**
     * Test invalid statement on select fetch
     * @dataProvider providerConnection
     * @param \Arch\DB\Driver $driver
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetch($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchAll($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchObject($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchColumn($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementInsert($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     */
    public function testFailStatementUpdate($driver, $database, $host, $user, $pass, $logger)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
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
     * @param string $database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param \Arch\Logger $logger
     * @param mixed $value The value to be tested
     */
    public function testInsertInvalidValue($driver, $database, $host, $user, $pass, $logger, $value)
    {
        $driver->connect($host, $database, $user, $pass);
        $driver->setLogger($logger);
        $table = new \Arch\DB\MySql\Table('test_table1', $driver);
        $result = $table->i(array('field1' => $value))->getInsertId();
        $this->assertFalse($result);
    }

}
