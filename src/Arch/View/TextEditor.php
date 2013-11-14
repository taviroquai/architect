<?php

namespace Arch\View;

/**
 * View Text Editor
 */
class TextEditor extends \Arch\View
{
	
    /**
     * Returns a new text editor view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','wysiwyg.php'));
        }
		parent::__construct($tmpl);
        
        $this->set('name', 'editor1');
        $this->set('value', '');
	}
}