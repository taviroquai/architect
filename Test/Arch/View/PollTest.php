<?php

/**
 * Description of PollTest
 *
 * @author mafonso
 */
class PollTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Poll();
        $this->assertInstanceOf('\Arch\View\Poll', $view);
    }
    
    /**
     * Test to string
     */
    public function testToString()
    {
        $view = new \Arch\View\Poll();
        $view->setVotes('vote1', 1);
        $this->assertInternalType('string', (string) $view);
    }
}
