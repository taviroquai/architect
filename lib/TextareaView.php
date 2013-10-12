<?php

class TextareaView extends View {
	
	public function __construct($tmpl = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/wysiwyg.php';
		parent::__construct($tmpl);
        
        // add view resources
        app()->theme->addContent(BASEURL.'theme/default/font-awesome/css/font-awesome.min.css', 'css');
        app()->theme->addContent(BASEURL.'theme/default/wysiwyg/index.css', 'css');
        app()->theme->addContent(BASEURL.'theme/default/wysiwyg/external/jquery.hotkeys.js', 'js');
        app()->theme->addContent(BASEURL.'theme/default/wysiwyg/bootstrap-wysiwyg.js', 'js');
	}
}