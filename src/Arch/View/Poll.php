<?php

namespace Arch\View;

/**
 * Poll class
 */
class Poll extends \Arch\Registry\View
{
    /**
     * Returns a new poll view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','pollchart.php'));
	parent::__construct($tmpl);
        
        // initialize data
        $this->storage['data'] = array();
        $this->storage['ykeys'] = 'y';
        $this->storage['show_votes'] = false;
        $this->storage['input_name'] = 'poll1';
    }
    
    /**
     * Sets a poll item
     * @param string $categoryName The name of the item
     * @param integer $votes The number of votes
     * @return \Arch\View\Poll
     */
    public function setVotes($categoryName, $votes)
    {
        $this->storage['data'][] = array(
            'x' => $categoryName, 
            'y' => $votes
        );
        return $this;
    }
    
}