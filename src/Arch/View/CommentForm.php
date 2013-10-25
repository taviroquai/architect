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
            $tmpl = BASE_PATH.'/theme/default/comment.php';
        }
		parent::__construct($tmpl);
	}
}