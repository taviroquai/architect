<?php

namespace Arch\View;

/**
 * View Pagination
 */
class Pagination extends \Arch\Registry\View
{
    
    /**
     * Holds the total pages
     * @var integer
     */
    protected $total   = 3;
    
    /**
     * Holds the base url
     * @var string
     */
    protected $url;
    
    /**
     * Holds the limit of items per page
     * @var integer
     */
    protected $limit   = 1;
    
    /**
     * Holds the pagination items; not the user items to be paged
     * @var array
     */
    protected $items   = array();
    
    /**
     * Holds the currect page number
     * @var integer
     */
    protected $current = 1;
    
    /**
     * Holds the user input
     * @var \Arch\IInput
     */
    protected $input;
	
    /**
     * Returns a new pagination view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','pagination.php'));
        parent::__construct($tmpl);
        
        $this->current = 1;
    }
    
    /**
     * Sets the user input
     * @param \Arch\IInput $input
     */
    public function setInput(\Arch\IInput $input)
    {
        $this->input = $input;
    }

        /**
     * Parses input and sets the current page and url
     */
    public function parseCurrent()
    {
        $this->url = $this->input->getRequestUri();
        if ($this->input->get('p'.$this->id) != false) {
            $current = $this->input->get('p'.$this->id);
            $current = $current > $this->total ? $this->total : $current;
            $current = $current < 1 ? 1 : $current;
            $this->current = $current;
            $pid = $this->input->get('p'.$this->id);
            $this->url = str_replace('&p'.$this->id.'='.$pid, '', $this->url);
        }
    }

    /**
     * Builds each page url
     * @param integer $page The page number
     * @return string
     */
    public function getUrl($page = 1)
    {
        if ($page < 1 || $page > $this->total) return '#';
        $url = $this->url;
        if (strpos($url, '?') === false) $url .= '?';
        $url .= '&p'.$this->id.'='.$page;
        return $url;
    }
    
    /**
     * Calculates the current items offset based on page numbr
     * @param integer $page The page number
     * @return type
     */
    public function getOffset($page = null)
    {
        if ($page === null) $page = $this->current;
        return ($page - 1 ) * $this->limit;
    }
    
    /**
     * Returns the limit of items per page
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Sets the limit of items per page
     * @param int $limit The limit of items per page
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
    }

    /**
     * Sets the total items
     * @param integer $total The total page items
     */
    public function setTotalItems($total)
    {
        $this->total = ceil($total / $this->limit);
    }
    
    /**
     * Renders the pagination
     * @return string
     */
    public function __toString()
    {
        // build items
        for ($i = 1; $i <= $this->total; $i++) {
            $item = (object) array(
                'text'  => $i, 
                'url'   => $this->getUrl($i),
                'class' => '');
            $this->items[$i] = $item;
        }
        
        // hide if there is only 1 page
        if ($this->total == 1) {
            $this->hide ();
        }
        
        $this->set('total', $this->total);
        $this->set('current', $this->current);
        $this->set('items', $this->items);
        $this->set('previous_url', $this->getUrl($this->current - 1));
        $this->set('next_url', $this->getUrl($this->current - 1));
        
        // render
        return parent::__toString();
    }
}