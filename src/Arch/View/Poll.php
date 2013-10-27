<?php

namespace Arch\View;

/**
 * Poll class
 */
class Poll extends \Arch\View
{
    
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/pollchart.php';
        }
		parent::__construct($tmpl);
        
        c(BASE_URL.'theme/default/morris/morris.css', 'css');
        c(BASE_URL.'theme/default/morris/raphael-min.js', 'js');
        c(BASE_URL.'theme/default/morris/morris.js', 'js');
        
        // initialize data
        $this->data['data'] = array();
        $this->data['ykeys'] = 'y';
	}
    
    public function setVotes($categoryName, $votes)
    {
        $this->data['data'][] = array(
            'x' => $categoryName, 
            'y' => $votes
        );
        return $this;
    }
    
}