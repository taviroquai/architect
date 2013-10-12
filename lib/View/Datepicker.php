<?php

class View_Datepicker extends View {
	
	public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/datepicker.php';
		parent::__construct($tmpl);
        
        // add view resources
        app()->addContent(BASEURL.'theme/default/datepicker/bootstrap-datetimepicker.min.css', 'css');
        app()->addContent(BASEURL.'theme/default/datepicker/bootstrap-datetimepicker.min.js', 'js');
	}
}