<?php

/**
 * Description of InputTest
 *
 * @author mafonso
 */
class InputTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Generates $_SERVER data
     * @return array
     */
    public function providerDataSERVER()
    {
        return array(
            array('', array('index.php', '/')),
            array('index.php/', array('index.php'))
        );
    }
    
    /**
     * Generates $_GET data
     * @return array
     */
    public function providerDataGET()
    {
        return array(
            array(array('a' => '1', 'b' => '2', 'c' => '3' ), 'b')
        );
    }
    
    /**
     * Generates $_POST data
     * @return array
     */
    public function providerDataPOST()
    {
        return array(
            array(array('a' => '1', 'b' => '2', 'c' => '3' ), 'b')
        );
    }
    
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
     * @param string $request_uri The $_SERVER['request_uri']
     * @param array $argv The $_SERVER['argv']
     * @dataProvider providerDataSERVER
     */
    public function testParseGlobalCli($request_uri, $argv)
    {
        $index = 1;
        $server = array('REQUEST_URI' => $request_uri, 'argv' => $argv);
        $expected = '/';
        if (isset($server['argv'][$index])) $expected = $server['argv'][$index];
        $input = new \Arch\Input();
        $input->parseGlobal('cli', $server);
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test cli params
     * @param string $request_uri The $_SERVER['request_uri']
     * @param array $argv The $_SERVER['argv']
     * @dataProvider providerDataSERVER
     */
    public function testCliParams($request_uri, $argv)
    {
        $server = array('REQUEST_URI' => $request_uri, 'argv' => $argv);
        $expected = $server['argv'];
        $input = new \Arch\Input();
        $input->parseGlobal('cli', $server);
        $result = $input->getParam();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test cli param by index
     * @param string $request_uri The $_SERVER['request_uri']
     * @param array $argv The $_SERVER['argv']
     * @dataProvider providerDataSERVER
     */
    public function testCliParamByIndex($request_uri, $argv)
    {
        $index = 0;
        $server = array('REQUEST_URI' => $request_uri, 'argv' => $argv);
        $expected = $server['argv'][$index];
        $input = new \Arch\Input();
        $input->parseGlobal('cli', $server);
        $result = $input->getParam($index);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test server params
     * @param string $request_uri The $_SERVER['request_uri']
     * @param array $argv The $_SERVER['argv']
     * @dataProvider providerDataSERVER
     */
    public function testServerParams($request_uri, $argv)
    {
        $server = array('REQUEST_URI' => $request_uri, 'argv' => $argv);
        $expected = $server;
        $input = new \Arch\Input();
        $input->parseGlobal('apache', $server);
        $result = $input->server();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test server param by key
     * @param string $request_uri The $_SERVER['request_uri']
     * @param array $argv The $_SERVER['argv']
     * @dataProvider providerDataSERVER
     */
    public function testServerParamByKey($request_uri, $argv)
    {
        $key = 'argv';
        $server = array('REQUEST_URI' => $request_uri, 'argv' => $argv);
        $expected = $server[$key];
        $input = new \Arch\Input();
        $input->setHttpServer($server);
        $result = $input->server($key);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP GET params
     * @param $get The $_GET params
     * @dataProvider providerDataGET
     */
    public function testGetParams($get)
    {
        $expected = $get;
        $input = new \Arch\Input();
        $input->setHttpGet($get);
        $result = $input->get();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP GET param by key
     * @param $get The $_GET params
     * @dataProvider providerDataGET
     */
    public function testGetParamByKey($get, $key)
    {
        $expected = $get[$key];
        $input = new \Arch\Input();
        $input->setHttpGet($get);
        $result = $input->get($key);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP POST params
     * @param $post The $_POST params
     * @dataProvider providerDataPOST
     */
    public function testPostParams($post)
    {
        $expected = $post;
        $input = new \Arch\Input();
        $input->setHttpPost($post);
        $result = $input->post();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test HTTP POST param by key
     * @param $post The $_POST params
     * @param $key The param
     * @dataProvider providerDataPOST
     */
    public function testPostParamByKey($post, $key)
    {
        $expected = $post[$key];
        $input = new \Arch\Input();
        $input->setHttpPost($post);
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
