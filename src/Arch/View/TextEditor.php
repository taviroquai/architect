<?php

namespace Arch\View;

/**
 * View Text Editor
 */
class TextEditor extends \Arch\View
{
	
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','wysiwyg.php'));
        }
		parent::__construct($tmpl);
        
        // add view resources
        $app = \Arch\App::Instance();
        $app->addContent($app->url('/arch/asset/css/font-awesome.min.css'),'css');
        $app->addContent($app->url('/arch/asset/css/wysiwyg.css'), 'css');
        $app->addContent($app->url('/arch/asset/js/jquery.hotkeys.js'), 'js');
        $app->addContent($app->url('/arch/asset/js/bootstrap-wysiwyg.js'), 'js');
	}
}