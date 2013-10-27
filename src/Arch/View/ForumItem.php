<?php

namespace Arch\View;

/**
 * ForumItem class
 */
class ForumItem extends \Arch\View
{
    
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/forumitem.php';
        }
		parent::__construct($tmpl);
        
        // init params
        $this->data['url'] = '/';
        $this->data['param'] = 'topic';
        $this->data['items'] = array();
	}
   
}