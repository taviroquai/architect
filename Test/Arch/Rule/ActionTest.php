<?php

/**
 * Description of ActionTest
 *
 * @author mafonso
 */
class ActionTest extends \PHPUnit_Framework_TestCase
{
    
    public function providerTestResult()
    {
        return array(
            array('k', 'required',  false,  array(),                array()),
            array('k', 'required',  false,  array('k' => ''),       array()),
            array('k', 'required',  true,   array('k' => 'value'),  array()),
            array('k', 'oneOf',     false,  array('k' => 'value'),  array(array(1))),
            array('k', 'oneOf',     true,   array('k' => 1),        array(array(1))),
            array('k', 'oneOf',     true,   array('k' => 1),        array(array('1'))),
            array('k', 'oneOf',     true,   array('k' => '1'),      array(array(1))),
            array('k', 'oneOf',     true,   array('k' => '1'),      array(function() { return array('a' => '1'); })),
            array('k', 'unique',    false,  array('k' => 1),        array(array(1))),
            array('k', 'unique',    true,   array('k' => 1),        array(array())),
            array('k', 'equals',    false,  array('k' => 1),        array('k2')),
            array('k', 'equals',    false,  array('k' => 1), array(0)),
            array('k', 'equals',    true,   array('k' => 1), array(1)),
            array('k', 'after',     false,  array('k' => '2013-01-01'), array('2013-01-01')),
            array('k', 'after',     false,  array('k' => '2013-01-01'), array('2013-01-02')),
            array('k', 'after',     true,   array('k' => '2013-01-02'), array('2013-01-01')),
            array('k', 'before',    false,  array('k' => '2013-01-01'), array('2013-01-01')),
            array('k', 'before',    true,   array('k' => '2013-01-01'), array('2013-01-02')),
            array('k', 'before',    false,  array('k' => '2013-01-02'), array('2013-01-01')),
            array('k', 'onInterval',true,   array('k' => '2013-01-01'), array('2013-01-01', '2013-01-01')),
            array('k', 'onInterval',false,  array('k' => '2013-01-02'), array('2013-01-01', '2013-01-01')),
            array('k', 'onInterval',true,   array('k' => '2013-01-02'), array('2013-01-01', '2013-01-03')),
            array('k', 'depends',   true,   array(),            array(array(false))),
            array('k', 'depends',   false,  array(),            array(array(true))),
            array('k', 'depends',   true,   array('k' => true), array(array(true))),
            array('k', 'depends',   true,   array('k' => true), array(array(false), true)),
            array('k', 'isDate',    false,  array('k' => false), array()),
            array('k', 'isDate',    false,  array('k' => '2013'), array()),
            array('k', 'isDate',    false,  array('k' => '2013-01'), array()),
            array('k', 'isDate',    true,   array('k' => '2013-01-01'), array()),
            array('k', 'isTime',    false,  array('k' => false), array()),
            array('k', 'isTime',    true,   array('k' => '00:00:00'), array()),
            array('k', 'isTime',    true,   array('k' => '00:00'), array('H:i')),
            array('k', 'isTime',    false,  array('k' => '2013-01-01'), array()),
            array('k', 'isTime',    true,   array('k' => '24:00:00'), array()),
            array('k', 'isTime',    true,   array('k' => '25:00:00'), array()),
            array('k', 'isEmail',   false,  array('k' => 'mail'), array()),
            array('k', 'isEmail',   false,  array('k' => 'a.b'), array()),
            array('k', 'isEmail',   false,  array('k' => 'a@b'), array()),
            array('k', 'isEmail',   false,  array('k' => 'a@b_c.d'), array()),
            array('k', 'isEmail',   true,   array('k' => 'a_b.c@d-e.f.g'), array()),
            array('k', 'isURL',     false,  array('k' => 'a'), array()),
            array('k', 'isURL',     false,  array('k' => 'a.com'), array()),
            array('k', 'isURL',     false,  array('k' => 'http://.com'), array()),
            array('k', 'isURL',     true,   array('k' => 'http://a.com'), array()),
            array('k', 'isImage',   false,  array('k' => RESOURCE_PATH.'dummy'), array()),
            array('k', 'isImage',   true,   array('k' => RESOURCE_PATH.'img/landscape.jpg'), array()),
            array('k', 'isInteger',   false,  array('k' => ''), array()),
            array('k', 'isInteger',   false,  array('k' => '0'), array()),
            array('k', 'isInteger',   false,  array('k' => 0.0), array()),
            array('k', 'isInteger',   true,   array('k' => 1), array()),
            array('k', 'isMime',    false,  array('k' => RESOURCE_PATH.'dummy'), array('image/jpeg')),
            array('k', 'isMime',    true,   array('k' => RESOURCE_PATH.'img/landscape.jpg'), array('image/jpeg')),
            array('k', 'isAlphaNum',false,  array('k' => 'a.0'), array()),
            array('k', 'isAlphaNum',false,  array('k' => 'a-0'), array()),
            array('k', 'isAlphaNum',false,  array('k' => 'a_0'), array()),
            array('k', 'isAlphaNum',true,  array('k' => 'a0'), array()),
            array('k', 'isAlphaExcept',true,  array('k' => 'a-0'), array()),
            array('k', 'isAlphaExcept',true,  array('k' => 'a-0'), array('-'))
        );
    }

    /**
     * Test create rule action
     */
    public function testCreate()
    {
        new \Arch\Rule\Action('param');
    }
    
    /**
     * Tests action result
     * @dataProvider providerTestResult
     */
    public function testResult($name, $method, $expected, $input, $params)
    {
        $action = new \Arch\Rule\Action($name, $input);
        if (is_callable(array($action, $method))) {
            $cbparams = array();
            if (isset($input[$name])) {
                $cbparams[] = $input[$name];
            } else {
                $cbparams[] = false;
            }
            foreach ($params as &$param) {
                $cbparams[] = $param;
            }
            $result = call_user_func_array(array($action, $method), $cbparams);
        }
        $this->assertEquals($expected, $result);
    }
}
