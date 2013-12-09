<?php

namespace Arch\View;

/**
 * View Text Editor
 */
class TextEditor extends \Arch\View
{
	
    /**
     * Returns a new text editor view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','wysiwyg.php'));
	parent::__construct($tmpl);
        
        $this->set('name', 'editor1');
        $this->set('value', '');
	}
}