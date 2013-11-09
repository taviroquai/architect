<?php

/**
 * Description of SessionTest
 *
 * @author mafonso
 */
class SessionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create session
     */
    public function testCreateSession()
    {
        new \Arch\Session();
    }
    
    /**
     * Test load data
     */
    public function testLoadData()
    {
        $expected = 'value';
        $data = array('param' => $expected);
        $session = new \Arch\Session();
        $session->load($data);
        $this->assertEquals($expected, $session->param);
    }
    
    /**
     * Test save data
     */
    public function testSaveData()
    {
        $expected = 'value';
        $target = array();
        $session = new \Arch\Session();
        $session->param = $expected;
        $session->save($target);
        $this->assertEquals($expected, $target['param']);
    }
    
    /**
     * Test save data
     */
    public function testSaveDataOverride()
    {
        $expected = 'value';
        $target = array('oldparam' => 'oldvalue');
        $session = new \Arch\Session();
        $session->param = $expected;
        $session->save($target);
        $this->assertEquals($expected, $target['param']);
    }
    
    /**
     * Test fail get param
     */
    public function testFailGetParam()
    {
        $expected = null;
        $session = new \Arch\Session();
        $this->assertEquals($expected, $session->param);
    }
    
    /**
     * Test set object param
     */
    public function testSetObjectParam()
    {
        $expected = new stdClass;
        $session = new \Arch\Session();
        $session->param = $expected;
        $this->assertEquals($expected, $session->param);
    }
    
    /**
     * Test unset object param
     */
    public function testUnsetParam()
    {
        $expected = null;
        $session = new \Arch\Session();
        $session->param = new stdClass;
        unset($session->param);
        $this->assertEquals($expected, $session->param);
    }
    
    /**
     * Test exists object param
     */
    public function testExistsParam()
    {
        $expected = true;
        $session = new \Arch\Session();
        $session->param = new stdClass;
        $result = isset($session->param);
        $this->assertEquals($expected, $result);
    }

}
