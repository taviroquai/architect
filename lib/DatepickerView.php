<?php

class DatepickerView extends View {
	
	public function __construct() {
		parent::__construct(BASEPATH.'/theme/default/datepicker.php');
        
        // add view resources
        app()->theme->addContent(BASEURL.'theme/default/datepicker/bootstrap-datetimepicker.min.css', 'css');
        app()->theme->addContent(BASEURL.'theme/default/datepicker/bootstrap-datetimepicker.min.js', 'js');
	}
}