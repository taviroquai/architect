<?php

namespace Arch\View;

/**
 * Comment form class
 */
class CommentForm extends \Arch\View
{
    /**
     * Returns a new comment form view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','comment.php'));
        parent::__construct($tmpl);
    }
}