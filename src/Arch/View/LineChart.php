<?php

namespace Arch\View;

/**
 * LineChart class
 */
class LineChart extends \Arch\View
{
    
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/linechart.php';
        }
		parent::__construct($tmpl);
        
        c(BASE_URL.'theme/default/morris/morris.css', 'css');
        c(BASE_URL.'theme/default/morris/raphael-min.js', 'js');
        c(BASE_URL.'theme/default/morris/morris.js', 'js');
	}
    
}