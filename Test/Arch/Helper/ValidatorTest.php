<?php

/**
 * Description of ValidatorTest
 *
 * @author mafonso
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $helper = new \Arch\Helper\Validator($app = new \Arch\App());
        $this->assertInstanceOf('\Arch\Helper\Validator', $helper);
    }
    
    /**
     * Test fail rule
     * @expectedException \Exception
     */
    public function testFailRule()
    {
        $helper = new \Arch\Helper\Validator($app = new \Arch\App());
        $helper->createRule('test', 'fail', 'test is required');
    }
    
    /**
     * Test execute
     */
    public function testExecute()
    {
        $helper = new \Arch\Helper\Validator($app = new \Arch\App());
        $helper->clearMessages();
        $rule = $helper->createRule('test', 'Required', 'test is required');
        $this->assertInstanceOf('\Arch\Rule\Required', $rule);
        $helper->setRules(array($rule));
        $result = $helper->execute();
        $this->assertInternalType('boolean', $result);
        $messages = $helper->getMessages();
        $this->assertInternalType('array', $messages);
    }
    
    public function providerRules()
    {
        return array(
            array('Required',       array()),
            array('After',          array(1, 2)),
            array('Before',         array(1, 2)),
            array('Between',        array(1, 2, 3)),
            array('Depends',        array(1, array())),
            array('Equals',         array(1, 1)),
            array('IsAlphaExcept',  array(1)),
            array('IsAlphaNumeric', array(1)),
            array('IsEmail',        array(1)),
            array('IsDate',         array(1)),
            array('IsImage',        array(RESOURCE_PATH.'img/portrait.jpg')),
            array('IsInteger',      array(1)),
            array('IsMime',         array(RESOURCE_PATH.'img/portrait.jpg', array())),
            array('IsTime',         array(1)),
            array('IsUrl',          array(1)),
            array('Matches',        array(1, '//')),
            array('OneOf',          array(1, array())),
            array('Unique',         array(1, array()))
        );
    }

    /**
     * Test execute
     * @dataProvider providerRules
     */
    public function testAllRules($rule_name, $params)
    {
        $helper = new \Arch\Helper\Validator($app = new \Arch\App());
        $rule = $helper->createRule('test', $rule_name, 'error message');
        $this->assertInstanceOf('\Arch\IRule', $rule);
        
        $rule->setParams($params);
        $helper->setRules(array($rule));
        $result = $helper->execute();
        $this->assertInternalType('boolean', $result);
    }
}
