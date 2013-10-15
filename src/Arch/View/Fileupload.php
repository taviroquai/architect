<?php

namespace Arch\View;

/**
 * View Fileupload
 */
class Fileupload extends \Arch\View
{
	
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/fileupload.php';
        }
		parent::__construct($tmpl);
        
        // add view resources
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/fileupload/bootstrap-fileupload.min.css',
            'css'
        );
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/fileupload/bootstrap-fileupload.min.js',
            'js'
        );
        
        $this->set('name', 'upload');
	}
}