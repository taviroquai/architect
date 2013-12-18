<?php

/**
 * Description of DatabaseTest
 *
 * @author mafonso
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */   
    public function testFailCreate()
    {
        $factory = new \Arch\Factory\Database();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\Factory\Database();
        $factory->create(\Arch::TYPE_DATABASE_MYSQL);
        $factory->create(\Arch::TYPE_DATABASE_SQLITE);
        $factory->create(\Arch::TYPE_DATABASE_PGSQL);
    }
}
