<?php

/**
 * Description of AppTest
 *
 * @author mafonso
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create application
     */
    public function testCreateApplication()
    {
        $config = RESOURCE_PATH.'/configValid.xml';
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
     * Test add message
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddMessage($app)
    {
        $app->addMessage('test');
        $expected = array(new \Arch\Message('test'));
        $result = $app->getMessages();
        $this->assertEquals($expected, $result);
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
        $app->addEvent(NULL, NULL);
    }
    
    /**
     * Test add event
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddEvent($app)
    {
        $event = $app->addEvent('test', function() {});
        $this->assertInstanceOf('Arch\Event', $event);
    }
    
    /**
     * Test trigger event
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testTriggerEvent($app)
    {
        $app->addEvent('test', function() {});
        $result = $app->triggerEvent('test');
        $this->assertInstanceOf('Arch\App', $result);
    }
    
    /**
     * Test add content
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testAddContent($app)
    {
        $expected = 'test';
        $app->addContent($expected, 'content');
        ob_start();
        $app->theme->slot('content', function($item) {
            echo $item;
        });
        $result = ob_get_clean();
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test create idiom
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateIdiom($app)
    {
        $app->session->set('idiom', false);
        $idiom = $app->createIdiom(null, 'default.xml', 'app');
        $this->assertInstanceOf('\Arch\Idiom', $idiom);
        $app->session->set('idiom', false);
        $app->config->set('DEFAULT_IDIOM', 'en');
        $idiom = $app->createIdiom(null, 'default.xml', 'app');
        $this->assertInstanceOf('\Arch\Idiom', $idiom);
        $app->session->set('idiom', 'en');
        $idiom = $app->createIdiom(null, 'default.xml', 'app');
        $this->assertInstanceOf('\Arch\Idiom', $idiom);
        $app->input->setHttpGet(array('idiom' => 'en'));
        $idiom = $app->createIdiom(null, 'default.xml', 'app');
        $this->assertInstanceOf('\Arch\Idiom', $idiom);
        $idiom = $app->createIdiom(null, 'other.xml');
        $this->assertInstanceOf('\Arch\Idiom', $idiom);
    }
    
    /**
     * Test create URL
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateURL($app)
    {
        $url = $app->url('/test');
        $result = (bool) filter_var($url, FILTER_VALIDATE_URL);
        $this->assertTrue($result);
    }
    
    /**
     * Test encrypt
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testEncrypt($app)
    {
        $expected = '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';
        $result = $app->encrypt('test');
        $this->assertEquals($expected, $result);
        
        $expected = '098f6bcd4621d373cade4e832627b4f6';
        $result = $app->encrypt('test', 'other');
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test get captcha
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testGetCaptcha($app)
    {
        $result = $app->getCaptcha();
        $this->assertFalse($result);
        
        $expected = 'test';
        $app->session->set('_captcha', $expected);
        $result = $app->getCaptcha();
        $this->assertFalse($result);
        
        $expected = 'test';
        $app->session->set('_captcha', $expected);
        $app->input->setHttpPost(array('_captcha' => $expected));
        $result = $app->getCaptcha();
        $this->assertEquals($expected, $result);
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
        $result = $app->upload($file, $targetDir);
        $this->assertFalse($result);
        
        $file = array('error' => 1);
        $targetDir = 'fail';
        $result = $app->upload($file, $targetDir);
        $this->assertFalse($result);
        
        $file = array('error' => 1);
        $targetDir = RESOURCE_PATH;
        $result = $app->upload($file, $targetDir, 'test', false);
        $this->assertFalse($result);
        
        $file = array('tmp_name' => RESOURCE_PATH.'dummy', 'name' => 'test');
        $targetDir = 'fail';
        $result = $app->upload($file, $targetDir);
        $this->assertFalse($result);
        
        $file = array('tmp_name' => RESOURCE_PATH.'dummy', 'name' => 'test');
        $targetDir = RESOURCE_PATH;
        $newName = '../dummy/test';
        $result = $app->upload($file, $targetDir, $newName);
        $this->assertFalse($result);
        
        $expected = RESOURCE_PATH.'img/test/test';
        copy(RESOURCE_PATH.'dummy', RESOURCE_PATH.'dummy_test');
        $file = array('tmp_name' => RESOURCE_PATH.'dummy_test', 'name' => 'test');
        $targetDir = RESOURCE_PATH.'img/test';
        $result = $app->upload($file, $targetDir);
        $this->assertEquals($expected, $result);
        unlink($expected);
        
        $expected = RESOURCE_PATH.'img/test/dummy_copy';
        copy(RESOURCE_PATH.'dummy', RESOURCE_PATH.'dummy_test');
        $file = array('tmp_name' => RESOURCE_PATH.'dummy_test', 'name' => 'test');
        $targetDir = RESOURCE_PATH.'img/test';
        $newname = 'dummy_copy';
        $result = $app->upload($file, $targetDir, $newname);
        $this->assertEquals($expected, $result);
        unlink($expected);
    }
    
    /**
     * Test fail download
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testFailDownload($app)
    {
        $result = $app->download(RESOURCE_PATH.'dumm');
        $this->assertFalse($result);
    }
    
    /**
     * Test download
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testDownload($app)
    {
        $result = $app->download(RESOURCE_PATH.'dummy');
        $this->assertTrue($result);
    }
    
    /**
     * Test slug
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testSlug($app)
    {
        $result = $app->slug('My.ó/ç');
        $expected = 'my-o-c';
        $this->assertEquals($expected, $result);
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
        $result = $app->createQuery('test_table1');
        $this->assertInstanceOf('Arch\DB\Table', $result);
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
            'table' => 'test_table2',
            'select'=> 'test_table2.*',
            'columns' => array(
                array('type' => 'value', 'property' => 'field1'),
                array('type' => 'action',   'icon'  => 'icon-edit', 
                'property' => 'id'),
            )
        );
        $autotable = $app->createAutoTable($config);
        $this->assertInternalType('string', (string)$autotable);
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
        // insert records to test relations
        $table1 = $app->createQuery('test_table1');
        $id1 = $table1->insert(array('field1' => 'test'))->getInsertId();
        $table1 = $app->createQuery('test_table2');
        $id2 = $table1->insert(array('field1' => 'test'))->getInsertId();
        $tablenm = $app->createQuery('test_nmrelation');
        $tablenm->insert(array('id_table1' => $id1, 'id_table2' => $id2))
                ->getInsertId();
        
        // test forms
        $config = array(
            'table' => 'test_table2',
            'select'=> 'test_table2.*',
            'record_id' => 1,
            'items' => array(
                array('type' => 'breakline'),
                array('type' => 'label', 'label' => 'field1'),
                array('type' => 'hidden',   'property'  => 'field1'),
                array('type' => 'password', 'property'  => 'field1'),
                array('type' => 'submit', 'label' => 'label', 'class' => 'btn'),
                array('type' => 'button', 'label' => 'label', 'action' => '#',
                    'class' => 'btn', 'onclick' => '', 'property' => 'id'),
                array('type' => 'text',     'property'  => 'field1'),
                array('type' => 'textarea', 'property'  => 'field1'),
                array('type' => 'checklist', 'property'  => 'id', 
                    'class' => 'checklist',
                    'items_table' => 'test_table1', 'prop_label' => 'id',
                    'selected_items_table' => 'test_nmrelation'),
                array('type' => 'radiolist', 'property'  => 'id', 
                    'class' => 'radiolist',
                    'items_table' => 'test_table1', 'prop_label' => 'id',
                    'selected_items_table' => 'test_nmrelation')
            )
        );
        $autoform = $app->createAutoForm($config);
        $this->assertInternalType('string', (string)$autoform);
        
        $config = array(
            'table' => 'test_nmrelation',
            'select'=> 'test_nmrelation.*',
            'record_id' => 1,
            'items' => array(
                array('type' => 'select', 'property'  => 'id',
                    'items_table' => 'test_table1', 'prop_label' => 'field1')
            )
        );
        $autoform = $app->createAutoForm($config);
        $this->assertInternalType('string', (string)$autoform);
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
        $result = $app->createImage($filename);
        $this->assertInstanceOf('Arch\Image', $result);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateValidator($app)
    {
        $result = $app->createValidator();
        $this->assertInstanceOf('Arch\Validator', $result);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateView($app)
    {
        $tmpl = RESOURCE_PATH.'template/div.php';
        $view = $app->createView($tmpl);
        $this->assertInstanceOf('Arch\View', $view);
        $this->assertInternalType('string', (string)$view);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateDatePicker($app)
    {
        $datepickerui = $app->createDatePicker();
        $this->assertInstanceOf('Arch\View\DatePicker', $datepickerui);
        $this->assertInternalType('string', (string)$datepickerui);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateFileUpload($app)
    {
        $uploadui = $app->createFileUpload();
        $this->assertInstanceOf('Arch\View\FileUpload', $uploadui);
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
        $this->assertInstanceOf('Arch\View\Pagination', $pagination);
        $pagination->getUrl();
        $pagination->setLimit(2);
        $pagination->setTotalItems(6);
        $pagination->getOffset();
        $this->assertInternalType('string', (string)$pagination);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateTextEditor($app)
    {
        $editor = $app->createTextEditor();
        $this->assertInstanceOf('Arch\View\TextEditor', $editor);
        $this->assertInternalType('string', (string)$editor);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCart($app)
    {
        $cart = $app->createCart();
        $this->assertInstanceOf('Arch\View\Cart', $cart);
        $item = (object) array('name' => 'Product1', 'price' => 30, 'tax' => 0.21);
        $cart->model->insertItem($item, 1, 2);
        $cart->model->getItem(1);
        $cart->model->addShippingOption('New');
        $cart->model->addPaymentOption('New');
        $cart->model->addCurrencyOption('USD');
        $cart->model->setCurrency('EUR');
        $cart->model->setShipping('Standard');
        $cart->model->setPayment('PayPal');
        $cart->model->updateQuantity(1, 3);
        $cart->model->updateTaxCost(5);
        $cart->model->updateShippingCost(5);
        $cart->model->updateQuantity(1, 0);
        $cart->model->getTotal();
        $cart->model->setUser(1);
        $this->assertInternalType('string', (string)$cart);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCaptcha($app)
    {
        $captcha = $app->createCaptcha();
        $this->assertInstanceOf('Arch\View', $captcha);
        $this->assertInternalType('string', (string)$captcha);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateBreadcrumbs($app)
    {
        $breadcrumbs = $app->createBreadcrumbs();
        $this->assertInstanceOf('Arch\View\Breadcrumbs', $breadcrumbs);
        $this->assertInternalType('string', (string)$breadcrumbs);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateCarousel($app)
    {
        $carousel = $app->createCarousel();
        $this->assertInstanceOf('Arch\View\Carousel', $carousel);
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
        $this->assertInstanceOf('Arch\View\CommentForm', $form);
        $this->assertInternalType('string', (string)$form);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateMap($app)
    {
        $map = $app->createMap();
        $this->assertInstanceOf('Arch\View\Map', $map);
        $marker = $map->model->createMarker(0, 0, 'Hello Architect!', true);
        $map->model->addMarker($marker);
        $this->assertInternalType('string', (string)$map);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateLineChart($app)
    {
        $linechart = $app->createLineChart();
        $this->assertInstanceOf('Arch\View\LineChart', $linechart);
        $this->assertInternalType('string', (string)$linechart);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreateTreeView($app)
    {
        $treeview = $app->createTreeView();
        $this->assertInstanceOf('Arch\View\TreeView', $treeview);
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
        $this->assertInstanceOf('Arch\View\FileExplorer', $explorer);
        $explorer->translatePath('/');
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
        $this->assertInstanceOf('Arch\View\ImageGallery', $gallery);
        $this->assertInternalType('string', (string)$gallery);
    }
    
    /**
     * @dataProvider providerApp
     * @param \Arch\App $app The application instance
     */
    public function testCreatePoll($app)
    {
        $poll = $app->createPoll();
        $this->assertInstanceOf('Arch\View\Poll', $poll);
        $poll->setVotes('category', 1);
        $this->assertInternalType('string', (string)$poll);
    }
    
}
