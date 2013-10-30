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
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','linechart.php'));
        }
		parent::__construct($tmpl);
        
        $app = \Arch\App::Instance();
        $app->addContent($app->url('/arch/asset/css/morris.css'), 'css');
        $app->addContent($app->url('/arch/asset/js/raphael-min.js'), 'js');
        $app->addContent($app->url('/arch/asset/js/morris.js'), 'js');
	}
    
}