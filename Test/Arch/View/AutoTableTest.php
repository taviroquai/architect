<?php

/**
 * Description of AutoTableTest
 *
 * @author mafonso
 */
class AutoTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\AutoTable();
        $this->assertInstanceOf('\Arch\View\AutoTable', $view);
    }
    
    /**
     * Test to string
     * @expectedException \Exception
     */
    public function testFailConfigColumns()
    {
        $view = new \Arch\View\AutoTable();
        $config = array(
            'table' => 'test_table1',
            'select' => 'test_table1.*'
        );
        $view->setConfig($config);
    }
    
    /**
     * Test fail set database
     * @expectedException \Exception
     */
    public function testFailSetDatabase()
    {
        $database = new \Arch\DB\MySql\Driver();
        $view = new \Arch\View\AutoTable();
        $view->setDatabaseDriver($database);
    }
    
    /**
     * Test fail set pagination
     * @expectedException \Exception
     */
    public function testFailSetPaginationNoConfig()
    {
        $view = new \Arch\View\AutoTable();
        $view->setPagination(new \Arch\View\Pagination());
    }
    
    /**
     * Test fail set pagination
     * @expectedException \Exception
     */
    public function testFailSetPaginationNoDatabase()
    {
        $view = new \Arch\View\AutoTable();
        $config = array(
            'table'     => 'test_table2',
            'select'    => 'test_table2.*',
            'columns'       => array()
        );
        $view->setConfig($config);
        $view->setPagination(new \Arch\View\Pagination());
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\AutoTable();
        $config = array(
            'table'     => 'test_table2',
            'select'    => 'test_table2.*',
            'columns'       => array(
                array('type' => 'value', 'label' => 'ID', 'property'  => 'id'),
                array('type' => 'value', 'label' => 'Email', 'property'  => 'id'),
                array('type' => 'action',   'icon'  => 'icon-edit', 
                    'action' => '/demo/crud/', 'property' => 'id'),
                array('type' => 'action',   'icon'  => 'icon-trash', 
                    'action' => '/demo/crud/del/', 'property' => 'id'),
                array('type' => 'action',   'icon'  => 'icon-trash', 
                    'property' => 'id')
            )
        );

        $view->setConfig($config);
        $db = new \Arch\DB\MySql\Driver();
        $db->connect(DB_HOST, DB_DATABASE, DB_USER, DB_PASS);
        $view->setDatabaseDriver($db);
        $pagination = new \Arch\View\Pagination();
        $pagination->setLimit(10);
        $view->setPagination($pagination);
        $view->getPagination();
        $this->assertInternalType('string', (string) $view);
    }
}
