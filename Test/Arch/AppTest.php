<?php

/**
 * Description of AppTest
 *
 * @author mafonso
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testCallConstructorForbidden()
    {
        register_shutdown_function(array($this, 'catchFatalCallToConstructor'));
        $filename = RESOURCE_PATH.'configValid.xml';
        new \Arch\App($filename);
    }
    
    public function catchFatalErrorCallToConstructor() {
        $this->fail('Cannot call application constructor');
    }
}
