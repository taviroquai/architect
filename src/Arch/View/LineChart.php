<?php

namespace Arch\View;

/**
 * LineChart class
 */
class LineChart extends \Arch\Registry\View
{
    /**
     * Returns a new line chart view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','linechart.php'));
	parent::__construct($tmpl);
        
        $this->setGraphValues(array(array('x' => 1, 'y' => 1)));
        $this->setYKeys(array('y'));
        $this->setLabels(array('label'));
    }
    
    /**
     * Sets the graph values
     * @param array $data An array of associative arrays containing values
     */
    public function setGraphValues(array $data)
    {
        $this->set('data', $data);
    }        

    /**
     * Sets the graph Y keys
     * @param array $keys An array of keys
     */
    public function setYKeys(array $keys)
    {
        $this->storage['ykeys'] = array('y');
    }
    
    /**
     * Sets the graph labels
     * @param array $labels An array of labels
     */
    public function setLabels(array $labels)
    {
        $this->storage['labels'] = array('label');
    }
    
}