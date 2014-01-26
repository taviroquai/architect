<?php

namespace Arch\Registry;

/**
 * View class
 */
class View extends \Arch\IRegistry
{
    /**
     * The view identifier
     * @var string
     */
    public $id;
    
    /**
     * Defines if the view should be rendered or not
     * @var boolean
     */
    protected $hidden;
    
    /**
     * Holds the template file
     * @var string
     */
    protected $template;
    
    /**
     * Holds the rendered view
     * @var string
     */
    protected $output;

    /**
     * Returns a new view
     * 
     * @param mixed $template The template, view or content
     * @param array $data The user data
     */
    public function __construct($template = '', array $data = array())
    {
        // reset properties
        $this->hidden = false;
        
        // check if $mixed is a file path
        if (file_exists($template)) {
            $this->template = $template;
        } else {
            // if not, consider it as output
            $this->output = $template;
        }
        // set default data
        $this->storage = $data;
        
        //set id
        $this->id = 'a'.substr(md5(microtime(true)),0,6);
    }
    
    /**
     * Sets the path template that will be used
     * @param string $template The template file
     * @return \Arch\Registry\View
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    /**
     * Hides the view
     * @return \Arch\Registry\View
     */
    public function hide()
    {
        $this->hidden = true;
        return $this;
    }
    
    /**
     * Shows the view
     * @return \Arch\Registry\View
     */
    public function show()
    {
        $this->hidden = false;
        return $this;
    }
    
    /**
     * Renders the view
     * @return string
     */
    public function __toString()
    {
        if ($this->hidden) {
            return '';
        }
        if (!file_exists($this->template)) {
            return $this->output;
        }
        $this->set('_id', $this->id);
        ob_start();
        if (is_array($this->storage)) {
            extract($this->storage);
        }
        include $this->template;
        $this->output = (string) ob_get_clean();
        return $this->output;
    }
}
