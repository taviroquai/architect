<?php

namespace Arch\View;

/**
 * Comment form class
 */
class CommentForm extends \Arch\View
{
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','comment.php'));
        }
		parent::__construct($tmpl);
	}
}