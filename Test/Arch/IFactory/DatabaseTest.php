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
        $factory = new \Arch\IFactory\DatabaseFactory();
        $factory->create(99);
    }
    
    /**
     * Test create
     */   
    public function testCreate()
    {
        $factory = new \Arch\IFactory\DatabaseFactory();
        $factory->create(\Arch\IFactory\DatabaseFactory::TYPE_MYSQL);
        $factory->create(\Arch\IFactory\DatabaseFactory::TYPE_SQLITE);
        $factory->create(\Arch\IFactory\DatabaseFactory::TYPE_PGSQL);
    }
}
