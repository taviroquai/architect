<?php

namespace Arch\View;

/**
 * View FileUpload
 */
class FileUpload extends \Arch\View
{
	/**
     * Returns a new file upload view
     * @param string $tmpl The template file
     */
    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','fileupload.php'));
        }
		parent::__construct($tmpl);
        
        $this->set('name', 'upload');
        $this->set('default_img', '');
    }
}