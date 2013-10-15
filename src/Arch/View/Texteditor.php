<?php

namespace Arch\View;

/**
 * View Texteditor
 */
class Texteditor extends \Arch\View
{
	
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/wysiwyg.php';
        }
		parent::__construct($tmpl);
        
        // add view resources
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/font-awesome/css/font-awesome.min.css',
            'css'
        );
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/wysiwyg/index.css',
            'css'
        );
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/wysiwyg/external/jquery.hotkeys.js',
            'js'
        );
        \Arch\App::Instance()->addContent(
            BASE_URL.'theme/default/wysiwyg/bootstrap-wysiwyg.js',
            'js'
        );
	}
}