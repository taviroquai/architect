<?php

/**
 * Description of CarouselTest
 *
 * @author mafonso
 */
class CarouselTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test create
     */
    public function testCreate()
    {
        $view = new \Arch\View\Carousel();
        $this->assertInstanceOf('\Arch\View\Carousel', $view);
    }
    
    /**
     * Test add item
     */
    public function testAddItem()
    {
        $view = new \Arch\View\Carousel();
        $view->addItem('<img src="#" />');
        $this->assertInternalType('string', (string) $view);
    }
}
