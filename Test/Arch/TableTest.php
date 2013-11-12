<?php

/**
 * Description of TableTest
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
                new \PDO(DB_DSN, DB_USER, DB_PASS, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )),
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
                new \PDO(DB_DSN, DB_USER, DB_PASS, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )),
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                null
            ),
            array(
                new \PDO(DB_DSN, DB_USER, DB_PASS, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )),
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                array()
            ),
            array(
                new \PDO(DB_DSN, DB_USER, DB_PASS, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )),
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                new stdClass()
            ),
            array(
                new \PDO(DB_DSN, DB_USER, DB_PASS, array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )),
                new \Arch\Logger(RESOURCE_PATH.'dummy'),
                function() { }
            )
        );
    }

    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testInvalidTable($pdo, $logger)
    {
        $result = new \Arch\Table(null, $pdo, $logger);
        $this->assertInstanceOf('\Arch\Table', $result);
    }
    
    /**
     * @expectedException \Exception
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testEmptyTable($pdo, $logger)
    {
        $result = new \Arch\Table('', $pdo, $logger);
        $this->assertInstanceOf('\Arch\Table', $result);
    }
    
    /**
     * Test create table
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testCreateTable($pdo, $logger)
    {
        $result = new \Arch\Table('test_table1', $pdo, $logger);
        $this->assertInstanceOf('\Arch\Table', $result);
    }
    
    /**
     * Test fail install
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailInstall($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->install(RESOURCE_PATH.'not_found');
        $this->assertFalse($result);
        $result = $table->install(RESOURCE_PATH.'db/fail.sql');
        $this->assertFalse($result);
    }
    
    /**
     * Test install
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testInstall($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->install(RESOURCE_PATH.'db/install.sql');
        $this->assertTrue($result);
    }
    
    /**
     * Test fail execute statement
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatement($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->run('select from');
        $this->assertFalse($result);
    }

    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailRun($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelect($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailSelect($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select with where
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectWhere($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->w(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail where
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailSelectWhere($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->w('field')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and wrong number of params
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailSelectWhereParams($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->w('field1 = ?', array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select, where and params
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectWhereParams($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->w('field1 = ?', array(1))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test select and join
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectJoin($pdo, $logger)
    {
        $table = new \Arch\Table('test_nmrelation', $pdo, $logger);
        $result = $table->s()->j(
                'test_table1',
                'test_table1.id = test_nmrelation.id_table1'
                )->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and group
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailSelectGroup($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->g('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and group
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectGroup($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->g('id')->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail select and limit
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailSelectLimit($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->l('dummy')->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test select and limit
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectLimit($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->l(1)->run();
        $this->assertInstanceOf('\PDOStatement', $result);
    }
    
    /**
     * Test fail insert
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailInsert($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->i(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test insert
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testInsert($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->i(array('field1' => 'insert'))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $id = $table->getInsertId();
        $this->assertNotEmpty($id);
        
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->i(array('field1' => false))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $id = $table->getInsertId();
        $this->assertNotEmpty($id);
    }
    
    /**
     * Test fail update
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailUpdate($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->u(array())->run();
        $this->assertFalse($result);
    }
    
    /**
     * Test update
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testUpdate($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->u(array('field1' => 'update'))->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $rows = $table->getRowCount();
        $this->assertInternalType('integer', $rows);
    }
    
    /**
     * Test select fetch all
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchAll($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->fetchAll();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test select fetch object
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchObject($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->fetchObject();
        $this->assertInstanceOf('\stdClass', $result);
    }
    
    /**
     * Test select fetch column
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testSelectFetchColumn($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()->fetchColumn();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test delete
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testDelete($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->d()->run();
        $this->assertInstanceOf('\PDOStatement', $result);
        $rows = $table->getRowCount();
        $this->assertInternalType('integer', $rows);
    }
    
    /**
     * Test invalid statement on select fetch all
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchAll($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchAll();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test invalid statement on select fetch object
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchObject($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchObject();
        $this->assertFalse($result);
    }
    
    /**
     * Test invalid statement on select fetch column
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatementSelectFetchColumn($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->s()
                ->where('field1 = ?', array(new stdClass))
                ->fetchColumn();
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Test invalid statement on insert
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatementInsert($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->i(array('field1' => 'insert'))
                ->where('field1 = ?', array(new stdClass))
                ->getInsertId();
        $this->assertFalse($result);
    }
    
    /**
     * Test invalid statement on update
     * @dataProvider providerConnection
     * @param \PDO $pdo
     * @param \Arch\Logger $logger
     */
    public function testFailStatementUpdate($pdo, $logger)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->u(array('field1' => 'update'))
                ->where('field1 = ?', array(new stdClass))
                ->getRowCount();
        $this->assertFalse($result);
    }
    
    /**
     * Test insert with invalid values
     * @dataProvider providerConnectionInsertInvalidValue
     * @param \PDO $pdo The PDO connection
     * @param \Arch\Logger $logger The logger
     * @param mixed $value The value to be tested
     */
    public function testInsertInvalidValue($pdo, $logger, $value)
    {
        $table = new \Arch\Table('test_table1', $pdo, $logger);
        $result = $table->i(array('field1' => $value))->getInsertId();
        $this->assertFalse($result);
    }

}
