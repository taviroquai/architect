<?php

/**
 * Description of POSTTest
 *
 * @author mafonso
 */
class POSTTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */   
    public function testCreate()
    {
        $input = new \Arch\Input\HTTP\POST();
        $this->assertInstanceOf('\Arch\Input\HTTP\POST', $input);
    }
    
    /**
     * Test create
     */
    public function testSetFiles()
    {
        $input = new \Arch\Input\HTTP\POST();
        
        $single = array(
            'upload' => array(
                'name'      => 'test1.png',
                'type'      => 'image/png',
                'tmp_name'  => '/tmp/phpmFkEUe',
                'error'     => 0,
                'size'      => 1000
            )
        );
        $input->setFiles($single);
        
        $multiple = array(
            'upload' => array( 
                'name'      => array('test1.png', 'test2.png'),
                'type'      => array('image/png', 'image/png'),
                'tmp_name'  => array('/tmp/phpmFkEUe', '/tmp/phpbLtZRw'),
                'error'     => array(0, 0), 
                'size'      => array(1000, 1000)
            )
        );
        $input->setFiles($multiple);
        
        $expected = array(
            'name'      => 'test2.png',
            'type'      => 'image/png',
            'tmp_name'  => '/tmp/phpbLtZRw',
            'error'     => 0, 
            'size'      => 1000
        );
        $result = $input->getFileByIndex(1);
        $this->assertEquals($expected, $result);
    }
}
