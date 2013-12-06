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
        $input = new \Arch\Input();
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
        $api = 'apache';
        $server = array('REQUEST_URI' => '/', 'argv' => array());
        $get = array('param' => null);
        $post = array('param' => null);
        $files = array(array('name' => ''));
        $raw = 'test';
        $input->parseGlobal($api, $server, $get, $post, $files, $raw);
        $input->sanitizeGet('param');
        $input->sanitizePost('param');
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
        if (isset($server['argv'][$index])) {
            $expected = $server['argv'][$index];
        }
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
        
        $expected = null;
        $input->parseGlobal('apache', $server, array('a' => $expected));
        $result = $input->getParam(0);
        $this->assertEquals($expected, $result);
        
        $expected = null;
        $input->parseGlobal('apache', $server, null, array('a' => $expected));
        $result = $input->getParam(0);
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
     * Test parse user action from cli
     */
    public function testParseCliAction()
    {
        $argv = array('index.php', '/');
        $server = array('REQUEST_URI' => null, 'argv' => $argv);
        $input = new \Arch\Input();
        $input->parseGlobal('cli', $server);
        
        $expected = '/';
        $input->parseAction();
        $result = $input->getAction();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test parse user action from web server
     */
    public function testParseUriAction()
    {
        $server = array('REQUEST_URI' => '/', 'argv' => array());
        $input = new \Arch\Input();
        $input->parseGlobal('apache', $server);
        
        $expected = '/';
        $input->parseAction();
        $result = $input->getAction();
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
        //$input->parseGlobal();
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
        $input->parseGlobal();
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
        $input->setHttpFiles($data);
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
        $input->setHttpFiles($data);
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
    
    public function testRule()
    {
        $input = new \Arch\Input();
        
        $rule = $input->createRule('param', 'Required', 'message');
        $this->assertInstanceOf('\Arch\Rule\Required', $rule);
        
        $result = $input->validate(array($rule));
        $this->assertInternalType('boolean', $result);
        
        $messages = $input->getMessages();
        $this->assertInternalType('array', $messages);
    }
    
    /**
     * Test validator of action with params
     */
    public function testValidationWithParams()
    {
        $get = array('param' => 'a');
        $params = array(array('a'));
        $input = new \Arch\Input();
        $input->parseGlobal();
        $input->setHttpGet($get);
        $rule = $input->createRule('param', 'OneOf', 'message');
        foreach ($params as &$param) {
            $rule->addParam($param);
        }
        $result = $input->validate(array($rule));
        $this->assertInternalType('boolean', $result);
    }
    
    /**
     * Tests invalid rule type
     * @expectedException \Exception
     */
    public function testInvalidValidationType()
    {
        $input = new \Arch\Input();
        $input->setHttpGet(array('params' => null));
        $input->createRule('param', '', '');
    }
    
    public function providerValidationTestData()
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
     * @dataProvider providerValidationTestData
     */
    public function testRuleResult($name, $rulename, $expected, $input, $params)
    {
        $validator = new \Arch\Input();
        $validator->setHttpGet($input);
        $rule = $validator->createRule($name, $rulename, 'message');
        foreach ($params as &$param) {
            $rule->addParam($param);
        }
        
        $rule->execute();
        $result = $rule->getResult();
        $this->assertEquals($expected, $result);
    }
}
