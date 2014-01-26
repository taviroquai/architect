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
        
        $this->storage['data'] = array(array('x' => 1, 'y' => 1));
        $this->storage['ykeys'] = array('y');
        $this->storage['labels'] = array('label');
    }
    
}