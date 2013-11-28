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
     * Test parse params by pattern
     */
    public function testParseActionParams()
    {
        $pattern = '/(:any)/(:num)';
        $action = '/test/1';
        $expected = array('test', '1');
        $router = new \Arch\Registry\Router();
        $result = $router->getActionParams($pattern, $action);
        $this->assertEquals($expected, $result);
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
        $pattern = 'test';
        $result = $router->getRouteCallback($pattern, new \Arch\Input);
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
        $pattern = NULL;
        $result = $router->getRouteCallback($pattern, new \Arch\Input);
        $this->assertInstanceOf('Closure', $result);
    }
}
