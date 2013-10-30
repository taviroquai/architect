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
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','fileupload.php'));
        }
		parent::__construct($tmpl);
        
        // add view resources
        $app = \Arch\App::Instance();
        $app->addContent(
            $app->url('/arch/asset/css/bootstrap-fileupload.min.css'),
            'css'
        );
        $app->addContent(
            $app->url('/arch/asset/js/bootstrap-fileupload.min.js'),
            'js'
        );
        
        $this->set('name', 'upload');
	}
}