<?php

// simulate session variable
if (!isset($_SESSION)) $_SESSION = array();
if (!is_dir(RESOURCE_PATH.'session')) mkdir (RESOURCE_PATH.'session');

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
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $this->assertInstanceOf('\Arch\Registry\Session\File', $session);
        
        $expected = RESOURCE_PATH.'session';
        $session->setPath($expected);
        $result = $session->getPath();
        $this->assertEquals($expected, $result);
        
        $session->generateId(md5('create'));
        $this->assertNotEmpty($session->id);
    }
    
    /**
     * Test create session
     * @expectedException \Exception
     */
    public function testFailLoad()
    {
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'forbidden');
        $session->generateId(md5('failload'));
        $session->load();
    }
    
    /**
     * Test create session
     */
    public function testSaveMessages()
    {
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->generateId(md5('save'));
        $session->createMessage('message');
        
        $expected = array(
            new \Arch\Message('message')
        );
        $result = $session->getMessages();
        $this->assertEquals($expected, $result);
        
        $session->save();
        $session->clearMessages();
        
        $session->load();
        $result = $session->getMessages();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test load data
     * @runInSeparateProcess
     */
    public function testLoadData()
    {
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->generateId(md5('load'));
        if (file_exists($session->getFilename())) unlink($session->getFilename());
        $session->load();
        $this->assertEmpty($session->get('param'));
        $expected = 'value';
        $session->set('param', $expected);
        $session->save();
        $session->load();
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test save data
     */
    public function testDataOverride()
    {
        $expected = 'value';
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->generateId(md5('override'));
        $session->set('param', 'oldvalue');
        $session->set('param', $expected);
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test reset session
     */
    public function testResetSession()
    {
        $expected = null;
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->generateId(md5('reset'));
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
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test set object param
     */
    public function testSetObjectParam()
    {
        $expected = new stdClass;
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->set('param', $expected);
        $this->assertEquals($expected, $session->get('param'));
    }
    
    /**
     * Test unset object param
     */
    public function testUnsetParam()
    {
        $expected = null;
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
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
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->set('param', new stdClass);
        $result = $session->exists('param');
        $this->assertEquals($expected, $result);
    }

    /**
     * Test get messages
     */
    public function testGetMessages()
    {
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
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
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
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
        
        $session = new \Arch\Registry\Session\File(RESOURCE_PATH.'session');
        $session->loadMessages($expected);
        $this->assertEquals($expected, $session->getMessages());
    }

}
