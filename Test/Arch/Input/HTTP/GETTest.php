<?php

/**
 * Description of GETTest
 *
 * @author mafonso
 */
class GETTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */   
    public function testCreate()
    {
        $input = new \Arch\Input\HTTP\GET();
        
        $server = array(
            'REQUEST_METHOD' => 'GET',
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
            'HTTP_HOST' => '127.0.0.1',
            'REQUEST_URI' => '/directory/index.php/action',
            'QUERY_STRING' => 'param=value'
        );
        $input->parseServer($server);
        $input->setParams(array('param' => 'value'));
        $input->parseAction(new \Arch\Registry\Config());
        $input->parseActionParams('/(:any)');
        $input->getActionParam();
        $input->getActionParam(0);
        $input->getActionParam(1);
        $input->isArchAction();
        $input->getAPI();
        $input->isCli();
        $input->getRaw();
        $input->getFileByIndex(0);
        $input->genCacheKey();
        $input->get();
        $input->get('param');
        $input->setAPI('apache2handler');
        $input->setRequestUri('/directory/index.php/action');
        $input->setQueryString('param=value');
        $input->setUserAgent('Mozilla/5.0');
        $input->setHttpHost('127.0.0.1');
        $input->setSecure(false);
        $input->getMethod();
        $input->getRequestUri();
        $input->getUserAgent();
        $input->getHttpHost();
        $input->isSecure();
        $input->sanitize('param');
        $input->setParams(array('param' => array('value')));
        $input->sanitize('param', FILTER_SANITIZE_NUMBER_INT);
        $input->sanitize('param', FILTER_SANITIZE_NUMBER_FLOAT);
    }
}
