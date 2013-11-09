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
        new \Arch\Rule();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidRuleMessage()
    {
        $rule = new \Arch\Rule();
        $rule->setErrorMessage(NULL);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testInvalidRuleAction()
    {
        $rule = new \Arch\Rule();
        $rule->setAction(NULL);
    }

    /**
     * Test create rule
     */
    
    public function testCreateRule()
    {
        $expected = 'test';
        $rule = new \Arch\Rule($expected);
        $result = $rule->getName();
        $this->assertEquals($expected, $result);
        
        $expected = 'exists';
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
