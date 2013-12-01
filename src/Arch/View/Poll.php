<?php

namespace Arch\View;

/**
 * Poll class
 */
class Poll extends \Arch\View
{
    /**
     * Returns a new poll view
     * @param string $tmpl The template file
     */
    public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','pollchart.php'));
        }
		parent::__construct($tmpl);
        
        // initialize data
        $this->data['data'] = array();
        $this->data['ykeys'] = 'y';
        $this->data['show_votes'] = false;
        $this->data['input_name'] = 'poll1';
    }
    
    /**
     * Sets a poll item
     * @param string $categoryName The name of the item
     * @param integer $votes The number of votes
     * @return \Arch\View\Poll
     */
    public function setVotes($categoryName, $votes)
    {
        $this->data['data'][] = array(
            'x' => $categoryName, 
            'y' => $votes
        );
        return $this;
    }
    
}