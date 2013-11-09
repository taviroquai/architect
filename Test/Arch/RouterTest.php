<?php

/**
 * Description of RouterTest
 *
 * @author mafonso
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Test create router
     */
    public function testCreateOutput()
    {
        new \Arch\Output();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddInvalidRouteKey()
    {
        $router = new \Arch\Router();
        $router->addRoute(null, null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddEmptyRouteKey()
    {
        $router = new \Arch\Router();
        $router->addRoute('', null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddInvalidRouteCallback()
    {
        $router = new \Arch\Router();
        $router->addRoute('test', null);
    }
    
    /**
     * Test add valid route
     */
    public function testAddValidRoute()
    {
        $expected = function() {
            return true;
        };
        $router = new \Arch\Router();
        $router->addRoute('test', $expected);
        $result = $router->getRoute('test', new \Arch\Input);
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test get invalid route
     */
    public function testGetInvalidRoute()
    {
        $router = new \Arch\Router();
        $router->addRoute('test', function() {
            return true;
        });
        $result = $router->getRoute(NULL, new \Arch\Input);
        $this->assertFalse($result);
    }
}
