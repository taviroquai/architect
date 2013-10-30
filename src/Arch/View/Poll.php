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
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','pollchart.php'));
        }
		parent::__construct($tmpl);
        
        $app = \Arch\App::Instance();
        $app->addContent($app->url('/arch/asset/css/morris.css'), 'css');
        $app->addContent($app->url('/arch/asset/js/raphael-min.js'), 'js');
        $app->addContent($app->url('/arch/asset/js/morris.js'), 'js');
        
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