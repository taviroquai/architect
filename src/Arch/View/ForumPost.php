<?php

namespace Arch\View;

/**
 * ForumPost class
 */
class ForumPost extends \Arch\View
{
    /**
     * Returns a new forum post view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','forumpost.php'));
        }
		parent::__construct($tmpl);
	}
   
}