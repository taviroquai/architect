<?php

class FileuploadView extends View {
	
	public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/fileupload.php';
		parent::__construct($tmpl);
        
        // add view resources
        app()->addContent(BASEURL.'theme/default/fileupload/bootstrap-fileupload.min.css', 'css');
        app()->addContent(BASEURL.'theme/default/fileupload/bootstrap-fileupload.min.js', 'js');
	}
}