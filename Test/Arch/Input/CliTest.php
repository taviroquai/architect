<?php

/**
 * Description of CliTest
 *
 * @author mafonso
 */
class CliTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */   
    public function testCreate()
    {
        $input = new \Arch\Input\CLI();
        
        $server = array(
            'SHELL' => '/bin/bash',
            'argv' => array('index.php', '/test')
        );
        $input->parseServer($server);
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
    }
}
