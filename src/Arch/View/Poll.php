<?php

namespace Arch\View;

/**
 * Poll class
 */
class Poll extends \Arch\View\LineChart
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
        $this->setGraphValues(array());
        $this->setYKeys(array('y'));
        $this->setLabels(array('label'));
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
