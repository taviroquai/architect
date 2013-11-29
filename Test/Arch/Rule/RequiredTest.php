<?php

/**
 * Description of RequiredTest
 *
 * @author mafonso
 */
class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testInvalidRule()
    {
        new \Arch\Rule\Required(null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidErrorMessage()
    {
        $rule = new \Arch\Rule\Required('param');
        $rule->setErrorMessage(NULL);
    }
    
    /**
     * Test create rule
     */
    
    public function testCreateRule()
    {
        $expected = 'test';
        $rule = new \Arch\Rule\Required($expected);
        $result = $rule->getName();
        $this->assertEquals($expected, $result);
        
        $expected = 'test';
        $rule->setErrorMessage($expected);
        $result = $rule->getErrorMessage();
        $this->assertEquals($expected, $result);
        
        $expected = true;
        $rule->setResult($expected);
        $result = $rule->getResult();
        $this->assertEquals($expected, $result);
        
        $expected = array();
        $rule->setParams(array());
        $result = $rule->getParams();
        $this->assertEquals($expected, $result);
        
        $expected = array('test');
        $rule->addParam('test');
        $result = $rule->getParams('test');
        $this->assertEquals($expected, $result);
    }
}
