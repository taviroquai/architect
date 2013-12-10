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
    public function testCreateRouter()
    {
        new \Arch\Registry\Router();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddInvalidRouteKey()
    {
        $router = new \Arch\Registry\Router();
        $router->addRoute(null, null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddEmptyRouteKey()
    {
        $router = new \Arch\Registry\Router();
        $router->addRoute('', null);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testAddInvalidRouteCallback()
    {
        $router = new \Arch\Registry\Router();
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
        $router = new \Arch\Registry\Router();
        $router->addRoute('test', $expected);
        $result = $router->getRouteCallback(new \Arch\Input\HTTP\GET());
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test get invalid route
     */
    public function testGetInvalidRoute()
    {
        $router = new \Arch\Registry\Router();
        $router->addRoute('test', function() {
            return true;
        });
        $result = $router->getRouteCallback(new \Arch\Input\HTTP\GET());
        $this->assertInstanceOf('Closure', $result);
    }
}
