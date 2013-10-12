<?php

class FileuploadView extends View {
	
	public function __construct() {
		parent::__construct(BASEPATH.'/theme/default/fileupload.php');
        
        // add view resources
        app()->theme->addContent(BASEURL.'theme/default/fileupload/bootstrap-fileupload.min.css', 'css');
        app()->theme->addContent(BASEURL.'theme/default/fileupload/bootstrap-fileupload.min.js', 'js');
	}
}