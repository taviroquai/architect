<?php

/**
 * Description of RuleTest
 *
 * @author mafonso
 */
class RuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testInvalidRule()
    {
        new \Arch\Rule(null, new \Arch\Input);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidAction()
    {
        $rule = new \Arch\Rule('param', new \Arch\Input);
        $rule->setAction(NULL);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidErrorMessage()
    {
        $rule = new \Arch\Rule('param', new \Arch\Input);
        $rule->setErrorMessage(NULL);
    }
    
    /**
     * Test create rule
     */
    
    public function testCreateRule()
    {
        $expected = 'test';
        $rule = new \Arch\Rule($expected, new \Arch\Input);
        $result = $rule->getName();
        $this->assertEquals($expected, $result);
        
        $expected = 'required';
        $result = $rule->getAction();
        $this->assertEquals($expected, $result);
        
        $expected = 'test';
        $rule->setAction($expected);
        $result = $rule->getAction();
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
