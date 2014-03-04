<?php

/**
 * Description of AutoFormTest
 *
 * @author mafonso
 */
class AutoFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\AutoForm();
        $this->assertInstanceOf('\Arch\View\AutoForm', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $db = new \Arch\DB\MySql\Driver();
        $db->connect(DB_HOST, DB_DATABASE, DB_USER, DB_PASS);
        
        $view = new \Arch\View\AutoForm();
        
        $config = array(
            'table'     => 'test_table1',
            'select'    => 'test_table1.*',
            'action'    => '/test',
            'record_id' => 2,
            'items'     => array(
                array('type' => 'hidden',   'property'  => 'id'),
                array('type' => 'label',    'label' => 'Email'),
                array('type' => 'text',     'property'  => 'field1'),
                array('type' => 'textarea',     'property'  => 'field1'),
                array('type' => 'password', 'property'  => 'password'),
                array('type' => 'checklist','property'  => 'id_table2', 
                    'class' => 'checklist inline',
                    'items_table' => 'test_table2', 'prop_label' => 'field1',
                    'selected_items_table' => 'test_nmrelation'),
                array('type' => 'radiolist','property'  => 'id_table2', 
                    'class' => 'checklist inline',
                    'items_table' => 'test_table2', 'prop_label' => 'field1',
                    'selected_items_table' => 'test_nmrelation'),
                array('type' => 'select', 'property'  => 'id_field1', 
                    'class' => 'checklist inline',
                    'items_table' => 'test_table2', 'prop_label' => 'field1',
                    'selected_items_table' => 'test_nmrelation'),
                array('type' => 'breakline'),
                array('type' => 'submit',   'label' => 'Save', 
                    'class' => 'btn btn-success inline'),
                array('type' => 'button',   'label' => 'Cancel', 'action' => '#',
                    'class' => 'btn inline', 'onclick' => 'window.history.back()',
                    'property' => 'id')
            )
        );

        $view->configure($config, $db);
        $this->assertInternalType('string', (string) $view);
        
        $view = new \Arch\View\AutoForm();
        
        $config = array(
            'table'     => 'test_nmrelation',
            'select'    => 'test_nmrelation.*',
            'action'    => '/test',
            'record_id' => 1,
            'items'     => array(
                array('type' => 'select', 'property'  => 'id_field1',
                    'items_table' => 'test_table2', 'prop_label' => 'field1')
            )
        );

        $view->configure($config, $db);
        $this->assertInternalType('string', (string) $view);
    }
}
