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
        $this->assertInstanceOf('\Arch\Rule', $rule);
        
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
        $validator = new \Arch\Validator($input);
        $rule = $validator->createRule('param');
        $rule->setAction('oneOf');
        $rule->setParams(array(array('a')));
        $validator->addRule($rule);
        $validator->validate();
        $result = $validator->getResult();
        $this->assertInternalType('boolean', $result);
    }
    
}
