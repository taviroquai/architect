<?php

namespace Arch\View;

/**
 * ForumTopic class
 */
class ForumTopic extends \Arch\View
{
    /**
     * Returns a new forum topic view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','forumtopic.php'));
        }
		parent::__construct($tmpl);
        
        // init params
        $this->data['url'] = '/';
        $this->data['param'] = 'forum';
        $this->data['items'] = array();
	}
   
}