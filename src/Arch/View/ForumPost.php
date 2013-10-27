<?php

namespace Arch\View;

/**
 * ForumPost class
 */
class ForumPost extends \Arch\View
{
    
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/forumpost.php';
        }
		parent::__construct($tmpl);
	}
   
}