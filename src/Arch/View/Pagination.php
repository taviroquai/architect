<?php

namespace Arch\View;

/**
 * View Pagination
 */
class Pagination extends \Arch\View
{
    /**
     * Holds the pagination ID; this will be used in URI to identify the
     * pagination item
     * @var string
     */
    public $id      = 1;
    
    /**
     * Holds the total pages
     * @var integer
     */
    public $total   = 3;
    
    /**
     * Holds the base url
     * @var string
     */
    public $url;
    
    /**
     * Holds the limit of items per page
     * @var integer
     */
    public $limit   = 1;
    
    /**
     * Holds the pagination items; not the user items to be paged
     * @var array
     */
    public $items   = array();
    
    /**
     * Holds the currect page number
     * @var integer
     */
    public $current = 1;
	
    /**
     * Returns a new pagination view
     * @param string $id The pagination id; this will be used in URI requests
     * @param integer $tmpl The template file
     */
	public function __construct($id = 1, $tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','pagination.php'));
        }
		parent::__construct($tmpl);
        
        // get pager id
        $this->id = $id;
        
        // get user input
        $this->current = 1;
	}
    
    /**
     * Parses input and sets the current page and url
     * @param \Arch\Input $input The application input
     */
    public function parseCurrent(\Arch\Input $input)
    {
        $this->url = $input->server('REQUEST_URI');
        if ($input->get('p'.$this->id) != false) {
            $this->current = $input->get('p'.$this->id);
            $pid = $input->get('p'.$this->id);
            $this->url = str_replace(
                '&p'.$this->id.'='.$pid, 
                '', 
                $input->server('REQUEST_URI')
            );
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
        
        // render
        return parent::__toString();
    }
}