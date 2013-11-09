<?php

/**
 * Description of InputTest
 *
 * @author mafonso
 */
class InputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create action
     */
    public function testCreateAction()
    {
        $expected = '/';
        $input = new \Arch\Input($expected);
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test default global input
     */
    public function testParseGlobalServer()
    {
        $expected = '/';
        $input = new \Arch\Input();
        $input->setRawInput('');
        $input->parseGlobal();
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test default cli
     */
    public function testParseGlobalCli()
    {
        $index = 1;
        $data = array('index.php', '/');
        $expected = $data[$index];
        $input = new \Arch\Input();
        $server = array('argv' => $expected);
        $input->parseGlobal('cli', $server);
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test cgi
     */
    public function testParseGlobalCgi()
    {
        $expected = '/';
        $input = new \Arch\Input();
        $input->parseGlobal('cgi');
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test cli params
     */
    public function testCliParams()
    {
        $expected = array('/', 'a', 'b', 'c');
        $input = new \Arch\Input();
        $server = array('argv' => $expected);
        $input->parseGlobal('cli', $server);
        $result = $input->getParam();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test cli param by index
     */
    public function testCliParamByIndex()
    {
        $data = array('/', 'a', 'b', 'c');
        $index = 1;
        $expected = $data[$index];
        $input = new \Arch\Input();
        $server = array('argv' => $data);
        $input->parseGlobal('cli', $server);
        $result = $input->getParam($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test server params
     */
    public function testServerParams()
    {
        $expected = array('a', 'b', 'c');
        $input = new \Arch\Input();
        $input->parseGlobal('apache', $expected);
        $result = $input->server();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test server param by key
     */
    public function testServerParamByKey()
    {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        $key = 'b';
        $expected = $data[$key];
        $input = new \Arch\Input();
        $input->setHttpServer($data);
        $result = $input->server($key);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP GET params
     */
    public function testGetParams()
    {
        $expected = array('a', 'b', 'c');
        $input = new \Arch\Input();
        $input->setHttpGet($expected);
        $input->parseGlobal('apache');
        $result = $input->get();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP GET param by index
     */
    public function testGetParamByIndex()
    {
        $data = array('a', 'b', 'c');
        $index = 0;
        $expected = $data[$index];
        $input = new \Arch\Input();
        $input->setHttpGet($data);
        $input->parseGlobal('apache');
        $result = $input->getParam($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP GET param by key
     */
    public function testGetParamByKey()
    {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        $key = 'b';
        $expected = $data[$key];
        $input = new \Arch\Input();
        $input->setHttpGet($data);
        $input->parseGlobal('apache');
        $result = $input->get($key);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP POST params
     */
    public function testPostParams()
    {
        $expected = array('a', 'b', 'c');
        $input = new \Arch\Input();
        $input->setHttpPost($expected);
        $input->parseGlobal('apache');
        $result = $input->post();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP POST param by index
     */
    public function testPostParamByIndex()
    {
        $data = array('a', 'b', 'c');
        $index = 0;
        $expected = $data[$index];
        $input = new \Arch\Input();
        $input->setHttpPost($data);
        $input->parseGlobal('apache');
        $result = $input->getParam($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP POST param by key
     */
    public function testPostParamByKey()
    {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        $key = 'b';
        $expected = $data[$key];
        $input = new \Arch\Input();
        $input->setHttpPost($data);
        $input->parseGlobal('apache');
        $result = $input->post($key);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test not found param by key
     */
    public function testNotFoundParam()
    {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        $key = 'd';
        $input = new \Arch\Input();
        $input->setHttpServer($data);
        $input->setHttpGet($data);
        $input->setHttpPost($data);
        $result1 = $input->server($key);
        $this->assertFalse($result1);
        $result2 = $input->get($key);
        $this->assertFalse($result2);
        $result3 = $input->post($key);
        $this->assertFalse($result3);
        $result4 = $input->getParam(999);
        $this->assertFalse($result4);
    }
    
    /**
     * Test single HTTP FILES
     */
    public function testFilesSingle()
    {
        $index = 0;
        $expected = array('name' => 'test');
        $data = array(
            'file' => $expected
        );
        $input = new \Arch\Input();
        $input->setHttpFiles($data['file']);
        $result = $input->file($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test multiple HTTP FILES
     */
    public function testFilesMultiple()
    {
        $index = 1;
        $expected = array('name' => 'test2');
        $data = array(
            'file' => array(
                'name' => array('test1', 'test2'))
        );
        $input = new \Arch\Input();
        $input->setHttpFiles($data['file']);
        $result = $input->file($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test not found HTTP FILES index
     */
    public function testNotFoundFileIndex()
    {
        $input = new \Arch\Input();
        $result = $input->file(0);
        $this->assertFalse($result);
    }
    
    /**
     * Test parse params by pattern
     */
    public function testParseActionParams()
    {
        $pattern = '/(:any)/(:num)';
        $action = '/test/1';
        $expected = array('test', '1');
        $input = new \Arch\Input();
        $input->getActionParams($pattern, $action);
        $result = $input->getParam();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test generate unique input key
     */
    public function testGenerateInputUniqueKey()
    {
        $input1 = new \Arch\Input('/1');
        $result1 = $input1->genCacheKey();
        
        $input2 = new \Arch\Input('/2');
        $result2 = $input2->genCacheKey();
        $this->assertNotEquals($result1, $result2);
    }
    
    /**
     * Tests whether or not it is a core action
     */
    public function testIsArchAction()
    {
        $input1 = new \Arch\Input('/arch');
        $result1 = $input1->isArchAction();
        $this->assertTrue($result1);
        
        $input2 = new \Arch\Input('/');
        $result2 = $input2->isArchAction();
        $this->assertFalse($result2);
        
        $input3 = new \Arch\Input('/dummy');
        $result3 = $input3->isArchAction();
        $this->assertFalse($result3);
    }
}
