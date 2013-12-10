<?php

/**
 * Description of ViewTest
 *
 * @author mafonso
 */
class ViewTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Provides data for testTemplateData
     * @return array
     */
    public function providerTemplateData()
    {
        return array(
            array('value', '<div>value</div>')
        );
    }

    /**
     * Test create view
     */
    public function testCreateView()
    {
        $view = new \Arch\Registry\View();
        $this->assertInstanceOf('\Arch\View', $view);
    }
    
    /**
     * Test render view content
     */
    public function testRenderContent()
    {
        $view = new \Arch\Registry\View();
        $result = (string) $view;
        $this->assertInternalType('string', $result);
        $expected = '';
        $this->assertEquals($expected, $result);
    }
    
    /**
     * Test render view with template
     */
    public function testRenderTemplate()
    {
        $expected = '<div></div>';
        $template = RESOURCE_PATH.'/template/div.php';
        $view = new \Arch\Registry\View($template);
        $this->assertEquals($expected, (string) $view);
        $view->setTemplate($template);
        $this->assertEquals($expected, (string) $view);
    }
    
    /**
     * Test hide
     */
    public function testHideShow()
    {
        
        $template = RESOURCE_PATH.'/template/div.php';
        $view = new \Arch\Registry\View($template);
        
        $expected = '';
        $view->hide();
        $this->assertEquals($expected, (string) $view);
        
        $expected = '<div></div>';
        $view->show();
        $this->assertEquals($expected, (string) $view);
    }
    
    /**
     * Test set and get template data
     */
    public function testSetGetData()
    {
        $template = RESOURCE_PATH.'/template/div.php';
        $view = new \Arch\Registry\View($template);
        
        $expected = null;
        $view->set('key', $expected);
        $this->assertEquals($expected, $view->get('key'));
    }
    
    /**
     * Test template data
     * @dataProvider providerTemplateData
     */
    public function testTemplateData($value, $expected)
    {
        $template = RESOURCE_PATH.'/template/div.php';
        $view = new \Arch\Registry\View($template);
        $view->set('key', $value);
        $this->assertEquals($expected, (string) $view);
        $this->assertEquals($value, $view->get('key'));
    }
    
    /**
     * Test template data
     */
    public function testSlots()
    {
        $template = RESOURCE_PATH.'/template/div.php';
        $view = new \Arch\Registry\View($template);
        
        $expected = array('content');
        $this->assertEquals($expected, $view->getSlots());
        
        $expected = array();
        $this->assertEquals($expected, $view->getSlotItems('content'));
        
        $expected = array('value');
        $view->addContent('value', 'slot1');
        $this->assertEquals($expected, $view->getSlotItems('slot1'));
        $view->addContent('value', 'slot1', true);
        $this->assertEquals($expected, $view->getSlotItems('slot1'));
        
        $expected = array('value');
        $view->addContent('value', 'js');
        $this->assertEquals($expected, $view->getSlotItems('js'));
        
        $expected = array();
        $view->emptySlot();
        $this->assertEquals($expected, $view->getSlotItems());
    }
    
    /**
     * Test slot template
     */
    public function testSlotTemplate()
    {
        $expected = '<p>value</p>';
        
        $view = new \Arch\Registry\View();
        $view->addContent('value');
        ob_start();
        $view->slot('content', function($item) {
            echo "<p>$item</p>";
        });
        $result = ob_get_clean();
        $this->assertEquals($expected, $result);
    }
}
