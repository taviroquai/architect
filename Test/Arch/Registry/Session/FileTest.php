<?php

// simulate session variable
if (!isset($_SESSION)) $_SESSION = array();

/**
 * Description of FileTest
 *
 * @author mafonso
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create session
     */
    public function testCreateSession()
    {
        $session = new \Arch\Registry\Session\File();
        $this->assertInstanceOf('\Arch\Registry\Session\File', $session);
    }
    
    /**
     * Test load data
     * @runInSeparateProcess
     */
    public function testLoadData()
    {
        $session = new \Arch\Registry\Session\File();
        $session->load($_SESSION);
        $this->assertEmpty($session->get('param'));
        $expected = 'value';
        $_SESSION['param'] = $expected;
        $session->load($_SESSION);
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test save data
     */
    public function testSaveData()
    {
        $expected = 'value';
        $_SESSION = array();
        $session = new \Arch\Registry\Session\File();
        $session->set('param', $expected);
        $session->save($_SESSION);
        $this->assertEquals($expected, $_SESSION['param']);
    }
    
    /**
     * Test save data
     */
    public function testSaveDataOverride()
    {
        $expected = 'value';
        $_SESSION['oldparam'] = 'oldvalue';
        $session = new \Arch\Registry\Session\File();
        $session->set('param', $expected);
        $session->save($_SESSION);
        $this->assertEquals($expected, $_SESSION['param']);
    }
    
    /**
     * Test reset session
     */
    public function testResetSession()
    {
        $expected = null;
        $session = new \Arch\Registry\Session\File();
        $session->set('param', 'value');
        $session->reset();
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test fail get param
     */
    public function testFailGetParam()
    {
        $expected = null;
        $session = new \Arch\Registry\Session\File();
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test set object param
     */
    public function testSetObjectParam()
    {
        $expected = new stdClass;
        $session = new \Arch\Registry\Session\File();
        $session->set('param', $expected);
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test unset object param
     */
    public function testUnsetParam()
    {
        $expected = null;
        $session = new \Arch\Registry\Session\File();
        $session->set('param', new stdClass);
        $session->delete('param');
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test exists object param
     */
    public function testExistsParam()
    {
        $expected = true;
        $session = new \Arch\Registry\Session\File();
        $session->set('param', new stdClass);
        $result = $session->exists('param');
        $this->assertEquals($expected, $result);
    }

    /**
     * Test get messages
     */
    public function testGetMessages()
    {
        $session = new \Arch\Registry\Session\File();
        $expected = array();
        $this->assertEquals($expected, $session->getMessages());
        
        $message = new \Arch\Message('test');
        $expected = array($message);
        $session->addMessage($message);
        $this->assertEquals($expected, $session->getMessages());
    }
    
    /**
     * Test clear messages
     */
    public function testClearMessages()
    {
        $session = new \Arch\Registry\Session\File();
        $expected = array();
        $session->addMessage(new \Arch\Message('test'));
        $session->clearMessages();
        $this->assertEquals($expected, $session->getMessages());
    }
    
    /**
     * Test load messages
     */
    public function testLoadMessages()
    {
        $message = new \Arch\Message('test');
        $expected = array($message);
        
        $session = new \Arch\Registry\Session\File();
        $session->loadMessages($expected);
        $this->assertEquals($expected, $session->getMessages());
    }

}
