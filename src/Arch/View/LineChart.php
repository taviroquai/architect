<?php

namespace Arch\View;

/**
 * LineChart class
 */
class LineChart extends \Arch\View
{
    /**
     * Returns a new line chart view
     * @param string $tmpl The template file
     */
    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','linechart.php'));
        }
	parent::__construct($tmpl);
        
        $this->data['data'] = array(array('x' => 1, 'y' => 1));
        $this->data['ykeys'] = array('y');
        $this->data['labels'] = array('label');
    }
    
}