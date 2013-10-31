<?php

namespace Arch\View;

/**
 * View Pagination
 */
class Pagination extends \Arch\View
{
    
    public $id      = 1;
    public $total   = 3;
    public $url;
    public $limit   = 1;
    public $items   = array();
    public $current = 1;
	
	public function __construct($id = 1, $tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','pagination.php'));
        }
		parent::__construct($tmpl);
        
        // get app
        $app = \Arch\App::Instance();
        
        // get pager id
        $this->id = $id;
        
        // get user input
        $this->current = 1;
        if ($app->input->get('p'.$this->id) != false) {
            $this->current = $app->input->get('p'.$this->id);
            $pid = $app->input->get('p'.$this->id);
            $this->url = str_replace(
                '&p'.$this->id.'='.$pid, 
                '', 
                $app->input->server('REQUEST_URI')
            );
        } else {
            $this->url = $app->input->server('REQUEST_URI');
        }
        
	}
    
    public function getUrl($page)
    {
        if ($page < 1 || $page > $this->total) return '#';
        $url = $this->url;
        if (strpos($url, '?') === false) $url .= '?';
        $url .= '&p'.$this->id.'='.$page;
        return $url;
    }
    
    public function getOffset($page)
    {
        return ($page - 1 ) * $this->limit;
    }
    
    public function setTotalItems($total)
    {
        $this->total = $total % $this->limit;
    }
    
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