<?php

/**
 * Description of database MysqlDriverTest
 *
 * @author mafonso
 */
class MySqlDriverTest extends \PHPUnit_Framework_TestCase
{
    
    public function testCreate()
    {
        $driver = new \Arch\DB\MySql\Driver(
            DB_DATABASE,
            DB_HOST,
            DB_USER,
            DB_PASS,
            new \Arch\Logger(RESOURCE_PATH.'dummy')
        );
        $this->assertInstanceOf('\Arch\DB\MySql\Driver', $driver);
    }
}
