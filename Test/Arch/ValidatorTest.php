<?php

/**
 * Description of ValidatorTest
 *
 * @author mafonso
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create validator
     */
    public function testCreate()
    {
        new \Arch\Validator();
    }
    
    public function testRule()
    {
        $input = array('param' => 'a');
        $validator = new \Arch\Validator();
        $this->assertEquals(0, $validator->countRules());
        
        $rule = $validator->createRule('param');
        $this->assertInstanceOf('\Arch\Rule\Required', $rule);
        
        $validator->addRule($rule);
        $this->assertEquals(1, $validator->countRules());
        
        $validator->validate();
        
        $result = $validator->getResult();
        $this->assertInternalType('boolean', $result);
        
        $messages = $validator->getMessages();
        $this->assertInternalType('array', $messages);
    }
    
    /**
     * Test validator of action with params
     */
    public function testActionWithParams()
    {
        $input = array('param' => 'a');
        $params = array(array('a'));
        $validator = new \Arch\Validator($input);
        $rule = $validator->createRule('param', 'OneOf');
        foreach ($params as &$param) {
            $rule->addParam($param);
        }
        $validator->addRule($rule);
        $validator->validate();
        $result = $validator->getResult();
        $this->assertInternalType('boolean', $result);
    }
    
    /**
     * Tests invalid rule type
     * @expectedException \Exception
     */
    public function testInvalidType()
    {
        $validator = new \Arch\Validator(array('params' => null));
        $validator->createRule('param', '');
    }
    
    public function providerTestResult()
    {
        return array(
            array('k', 'Required',  false,  array(),                array()),
            array('k', 'Required',  false,  array('k' => ''),       array()),
            array('k', 'Required',  true,   array('k' => 'value'),  array()),
            array('k', 'OneOf',     false,  array('k' => 'value'),  array(array(1))),
            array('k', 'OneOf',     true,   array('k' => 1),        array(array(1))),
            array('k', 'OneOf',     true,   array('k' => 1),        array(array('1'))),
            array('k', 'OneOf',     true,   array('k' => '1'),      array(array(1))),
            array('k', 'OneOf',     true,   array('k' => '1'),      array(function() { return array('a' => '1'); })),
            array('k', 'Unique',    false,  array('k' => 1),        array(array(1))),
            array('k', 'Unique',    false,  array('k' => 1),        array(function() { return array('a' => 1); })),
            array('k', 'Unique',    true,   array('k' => 1),        array(array())),
            array('k', 'Equals',    false,  array('k' => 1),        array('k2')),
            array('k', 'Equals',    false,  array('k' => 1), array(0)),
            array('k', 'Equals',    true,   array('k' => 1), array(1)),
            array('k', 'After',     false,  array('k' => '2013-01-01'), array('2013-01-01')),
            array('k', 'After',     false,  array('k' => '2013-01-01'), array('2013-01-02')),
            array('k', 'After',     true,   array('k' => '2013-01-02'), array('2013-01-01')),
            array('k', 'Before',    false,  array('k' => '2013-01-01'), array('2013-01-01')),
            array('k', 'Before',    true,   array('k' => '2013-01-01'), array('2013-01-02')),
            array('k', 'Before',    false,  array('k' => '2013-01-02'), array('2013-01-01')),
            array('k', 'Between',   true,   array('k' => '2013-01-01'), array('2013-01-01', '2013-01-01')),
            array('k', 'Between',   false,  array('k' => '2013-01-02'), array('2013-01-01', '2013-01-01')),
            array('k', 'Between',   true,   array('k' => '2013-01-02'), array('2013-01-01', '2013-01-03')),
            array('k', 'Depends',   true,   array(),            array(array(false))),
            array('k', 'Depends',   false,  array(),            array(array(true))),
            array('k', 'Depends',   true,   array('k' => true), array(array(true))),
            array('k', 'Depends',   true,   array('k' => true), array(array(false), true)),
            array('k', 'IsDate',    false,  array('k' => false), array()),
            array('k', 'IsDate',    false,  array('k' => '2013'), array()),
            array('k', 'IsDate',    false,  array('k' => '2013-01'), array()),
            array('k', 'IsDate',    true,   array('k' => '2013-01-01'), array()),
            array('k', 'IsTime',    false,  array('k' => false), array()),
            array('k', 'IsTime',    true,   array('k' => '00:00:00'), array()),
            array('k', 'IsTime',    true,   array('k' => '00:00'), array('H:i')),
            array('k', 'IsTime',    false,  array('k' => '2013-01-01'), array()),
            array('k', 'IsTime',    true,   array('k' => '24:00:00'), array()),
            array('k', 'IsTime',    true,   array('k' => '25:00:00'), array()),
            array('k', 'IsEmail',   false,  array('k' => 'mail'), array()),
            array('k', 'IsEmail',   false,  array('k' => 'a.b'), array()),
            array('k', 'IsEmail',   false,  array('k' => 'a@b'), array()),
            array('k', 'IsEmail',   false,  array('k' => 'a@b_c.d'), array()),
            array('k', 'IsEmail',   true,   array('k' => 'a_b.c@d-e.f.g'), array()),
            array('k', 'IsUrl',     false,  array('k' => 'a'), array()),
            array('k', 'IsUrl',     false,  array('k' => 'a.com'), array()),
            array('k', 'IsUrl',     false,  array('k' => 'http://.com'), array()),
            array('k', 'IsUrl',     true,   array('k' => 'http://a.com'), array()),
            array('k', 'IsImage',   false,  array('k' => RESOURCE_PATH.'dummy'), array()),
            array('k', 'IsImage',   true,   array('k' => RESOURCE_PATH.'img/landscape.jpg'), array()),
            array('k', 'IsInteger',   false,  array('k' => ''), array()),
            array('k', 'IsInteger',   false,  array('k' => '0'), array()),
            array('k', 'IsInteger',   false,  array('k' => 0.0), array()),
            array('k', 'IsInteger',   true,   array('k' => 1), array()),
            array('k', 'IsMime',    false,  array('k' => RESOURCE_PATH.'dummy'), array('image/jpeg')),
            array('k', 'IsMime',    true,   array('k' => RESOURCE_PATH.'img/landscape.jpg'), array('image/jpeg')),
            array('k', 'IsAlphaNumeric',false,  array('k' => 'a.0'), array()),
            array('k', 'IsAlphaNumeric',false,  array('k' => 'a-0'), array()),
            array('k', 'IsAlphaNumeric',false,  array('k' => 'a_0'), array()),
            array('k', 'IsAlphaNumeric',true,  array('k' => 'a0'), array()),
            array('k', 'IsAlphaExcept',true,  array('k' => 'a-0'), array()),
            array('k', 'IsAlphaExcept',true,  array('k' => 'a-0'), array('-')),
            array('k', 'Matches',   true,  array('k' => 'matches'), array('/matches/i'))
        );
    }
    
    /**
     * Tests action result
     * @dataProvider providerTestResult
     */
    public function testResult($name, $rulename, $expected, $input, $params)
    {
        $validator = new \Arch\Validator($input);
        $rule = $validator->createRule($name, $rulename);
        foreach ($params as &$param) {
            $rule->addParam($param);
        }
        
        $rule->execute();
        $result = $rule->getResult();
        $this->assertEquals($expected, $result);
    }
}
