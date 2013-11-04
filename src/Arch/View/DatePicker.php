<?php

namespace Arch\View;

/**
 * View datepicker
 */
class DatePicker extends \Arch\View
{
	
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','datepicker.php'));
        }
		parent::__construct($tmpl);
        
        // add view resources
        $app = \Arch\App::Instance();
        $app->addContent(
            $app->url('/arch/asset/css/bootstrap-datetimepicker.min.css'),
            'css'
        );
        $app->addContent(
            $app->url('/arch/asset/js/bootstrap-datetimepicker.min.js'),
            'js'
        );
        
        $this->set('default', date('Y-m-d'));
	}
}