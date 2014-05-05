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
        
        $db = new \Arch\DB\SQLite\Driver();
        $db->connect(DB_HOST, DB_DATABASE, DB_USER, DB_PASS);
        $view->configure($config, $db);
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

        $db = new \Arch\DB\SQLite\Driver();
        $db->connect(DB_HOST, DB_DATABASE, DB_USER, DB_PASS);
        $view->configure($config, $db);
        $pagination = new \Arch\View\Pagination();
        $pagination->setLimit(10);
        $view->setPagination($pagination);
        $view->getPagination();
        $this->assertInternalType('string', (string) $view);
    }
}
