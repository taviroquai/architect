<?php

namespace Arch\View;

/**
 * View datepicker
 */
class Datepicker extends \Arch\View
{
	
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/datepicker.php';
        }
		parent::__construct($tmpl);
        
        // add view resources
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/datepicker/bootstrap-datetimepicker.min.css',
            'css'
        );
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/datepicker/bootstrap-datetimepicker.min.js',
            'js'
        );
	}
}