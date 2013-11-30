<?php

/**
 * Description of AppTest
 *
 * @author mafonso
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test fail create
     * @expectedException \Exception
     */
    public function testFailCreateApplication()
    {
        \Arch\App::Instance();
    }

    /**
     * Test create application
     */
    public function testCreateApplication()
    {
        $config = RESOURCE_PATH.'/configValid.xml';
        $app = \Arch\App::Instance($config);
        $this->assertInstanceOf('\Arch\App', $app);
        $app = new \Arch\App($config);
        $this->assertInstanceOf('\Arch\App', $app);
    }
    
    public function providerApp()
    {
        return array(
            array(new Arch\App(RESOURCE_PATH.'/configValid.xml'))
        );
    }

    /**
     * Test run application
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testRunApplication($app)
    {
        $result1 = $app->run();
        $this->assertTrue($result1);
        $result2 = $app->run();
        $this->assertFalse($result2);
    }
    
    /**
     * Test run application no modules
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testRunApplicationNoModules($app)
    {   
        $app->config->set('MODULE_PATH', '');
        $result1 = $app->run();
        $this->assertTrue($result1);
    }
    
    /**
     * Test set output
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testSetOutput($app)
    {
        $expected = 'test';
        $app->output($expected);
        $this->assertEquals($expected, $app->output->getContent());
    }
    
    /**
     * Test redirect
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testRedirect($app)
    {
        //$app->redirect('http://localhost');
    }
    
    /**
     * Test send JSON
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testSendJSON($app)
    {
        $app->sendJSON(array());
    }
    
    /**
     * Test fail add route
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailAddRoute($app)
    {
        $app->addRoute(NULL, NULL);
    }
    
    /**
     * Test add route
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddRoute($app)
    {
        $app->addRoute('/', function() {});
    }
    
    /**
     * Test fail add message
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailAddMessage($app)
    {
        $app->addMessage(NULL);
    }
    
    /**
     * Test add message
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddMessage($app)
    {
        $app->addMessage('test');
    }
    
    /**
     * Test get messages
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testGetMessages($app)
    {
        $result = $app->getMessages();
        $this->assertEquals(array(), $result);
    }
    
    /**
     * Test clear messages
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testClearMessages($app)
    {
        $app->addMessage('test');
        $app->clearMessages();
        $result = $app->getMessages();
        $this->assertEquals(array(), $result);
    }
    
    /**
     * Test fail add event
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailAddEvent($app)
    {
        $app->addEvent(NULL);
    }
    
    /**
     * Test add event
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddEvent($app)
    {
        $app->addEvent('test', function() {});
    }
    
    /**
     * Test add content
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddContent($app)
    {
        $app->addContent('test');
    }
    
    /**
     * Test create URL
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateURL($app)
    {
        $app->url('/test');
    }
    
    /**
     * Test encrypt
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testEncrypt($app)
    {
        $app->encrypt('test');
        $app->encrypt('test', 'other');
    }
    
    /**
     * Test get captcha
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testGetCaptcha($app)
    {
        $app->getCaptcha();
        $app->session->set('_captcha', 'test');
        $app->getCaptcha();
    }
    
    /**
     * Test http Get
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testHttpGet($app)
    {
        $app->httpGet('http://localhost');
    }
    
    /**
     * Test http Post
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testHttpPost($app)
    {
        $app->httpPost('http://localhost', array('param' => 1));
    }
    
    /**
     * Test upload
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testUpload($app)
    {
        $file = null;
        $targetDir = 'fail';
        $app->upload($file, $targetDir);
        $file = array('error' => 1);
        $app->upload($file, $targetDir);
        $file = array('tmp_name' => RESOURCE_PATH.'dummy', 'name' => 'dummy');
        $app->upload($file, $targetDir);
        $targetDir = RESOURCE_PATH.'img/test';
        $app->upload($file, $targetDir);
        $newname = 'dummy_copy';
        $app->upload($file, $targetDir, $newname);
    }
    
    /**
     * Test fail download
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testFailDownload($app)
    {
        //$app->download(RESOURCE_PATH.'dumm');
    }
    
    /**
     * Test download
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testDownload($app)
    {
        $app->download(RESOURCE_PATH.'dummy');
    }
    
    /**
     * Test slug
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testSlug($app)
    {
        $app->slug('My.ó/ç');
    }
    
    /**
     * Test fail init database
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailInitDatabase($app)
    {
        $app->config->set('DB_USER', 'none');
        $app->createQuery('test_table1');
    }
    
    /**
     * Test fail create query
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailCreateQuery($app)
    {
        $app->config->set('DB_USER', 'none');
        $app->createQuery('test_table1');
    }

    /**
     * Test create query
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateQuery($app)
    {
        $app->createQuery('test_table1');
    }
    
    /**
     * Fail AutoTable data provider
     * @return array
     */
    public function providerFailAutoTable()
    {
        return array(
            array(
                new Arch\App(RESOURCE_PATH.'/configValid.xml'),
                array()
            ),
            array(
                new Arch\App(RESOURCE_PATH.'/configValid.xml'),
                array('table' => '1')
            ),
            array(
                new Arch\App(RESOURCE_PATH.'/configValid.xml'),
                array('table' => '1', 'select' => '1')
            )
        );
    }
    
    /**
     * Test fail auto table
     * @dataProvider providerFailAutoTable
     * @param \Arch\App $app The application instance
     * @param array $config The AutoTable configuration
     * @expectedException \Exception
     */
    public function testFailCreateAutoTable($app, $config)
    {
        $app->createAutoTable($config);
    }
    
    /**
     * Test fail auto table
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateAutoTable($app)
    {
        $config = array(
            'table' => 'test_table1',
            'select'=> 'test_table1.*',
            'columns' => array(
                array('type' => 'value', 'property' => 'field1')
            )
        );
        $app->createAutoTable($config);
    }
    
    /**
     * Fail AutoTable data provider
     * @return array
     */
    public function providerFailAutoForm()
    {
        return array(
            array(
                new Arch\App(RESOURCE_PATH.'/configValid.xml'),
                array()
            ),
            array(
                new Arch\App(RESOURCE_PATH.'/configValid.xml'),
                array('table' => '1')
            )
        );
    }
    
    /**
     * Test fail auto table
     * @dataProvider providerFailAutoForm
     * @param \Arch\App $app The application instance
     * @param array $config The AutoTable configuration
     * @expectedException \Exception
     */
    public function testFailCreateAutoForm($app, $config)
    {
        $app->createAutoForm($config);
    }
    
    /**
     * Test fail auto table
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateAutoForm($app)
    {
        $config = array(
            'table' => 'test_table1',
            'select'=> 'test_table1.*',
            'items' => array(
                array('type' => 'label', 'label' => 'filed1')
            )
        );
        $app->createAutoForm($config);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     * @expectedException \Exception
     */
    public function testFailCreateImage($app)
    {
        $filename = '';
        $app->createImage($filename);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateImage($app)
    {
        $filename = RESOURCE_PATH.'img/landscape.jpg';
        $app->createImage($filename);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateValidator($app)
    {
        $app->createValidator();
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateView($app)
    {
        $tmpl = RESOURCE_PATH.'template/div.php';
        $view = $app->createView($tmpl);
        $this->assertInternalType('string', (string)$view);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateDatePicker($app)
    {
        $datepickerui = $app->createDatePicker();
        $this->assertInternalType('string', (string)$datepickerui);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateFileUpload($app)
    {
        $uploadui = $app->createFileUpload();
        $this->assertInternalType('string', (string)$uploadui);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreatePagination($app)
    {
        $app->input->setHttpGet(array('p1' => 1));
        $pagination = $app->createPagination();
        $pagination->getUrl();
        $pagination->getOffset();
        $pagination->setTotalItems(10);
        $this->assertInternalType('string', (string)$pagination);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateTextEditor($app)
    {
        $editor = $app->createTextEditor();
        $this->assertInternalType('string', (string)$editor);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCart($app)
    {
        $cart = $app->createCart();
        $this->assertInternalType('string', (string)$cart);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCaptcha($app)
    {
        $captcha = $app->createCaptcha();
        $this->assertInternalType('string', (string)$captcha);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateBreadcrumbs($app)
    {
        $breadcrumbs = $app->createBreadcrumbs();
        $this->assertInternalType('string', (string)$breadcrumbs);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCarousel($app)
    {
        $carousel = $app->createCarousel();
        $carousel->addItem('<span></span>');
        $this->assertInternalType('string', (string)$carousel);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCommentForm($app)
    {
        $form = $app->createCommentForm();
        $this->assertInternalType('string', (string)$form);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateMap($app)
    {
        $map = $app->createMap();
        $this->assertInternalType('string', (string)$map);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateLineChart($app)
    {
        $linechart = $app->createLineChart();
        $this->assertInternalType('string', (string)$linechart);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateTreeView($app)
    {
        $treeview = $app->createTreeView();
        $treeview->getRoot();
        $treeview->createNode('test', 'test');
        $this->assertInternalType('string', (string)$treeview);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateFileExplorer($app)
    {
        $explorer = $app->createFileExplorer(RESOURCE_PATH);
        $explorer->setPathToUrl(function($path){});
        $explorer->translatePath('/');
        $explorer->setInputParam('/');
        $explorer->getPath();
        $explorer->getFolders();
        $explorer->getFiles();
        $this->assertInternalType('string', (string)$explorer);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateImageGallery($app)
    {
        $gallery = $app->createImageGallery(RESOURCE_PATH);
        $this->assertInternalType('string', (string)$gallery);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreatePoll($app)
    {
        $poll = $app->createPoll();
        $poll->setVotes('category', 1);
        $this->assertInternalType('string', (string)$poll);
    }
    
}
