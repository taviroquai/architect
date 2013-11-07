<?php

namespace Arch;

/**
 * View class
 */
class View
{
    public $id;
    protected $hidden = false;
    protected $data = array();
    protected $slot = array('content' => array());
    protected $template;
    protected $output;

    /**
     * Constructor
     * 
     * @param mixed $mixed
     * @param array $data
     */
    public function __construct($mixed = '', $data = array())
    {
        // check if $mixed is a file path
        if (file_exists($mixed)) {
            $this->template = $mixed;
        } else {
            // if not, consider it as content
            $this->output = $mixed;
        }
        // set default data
        $this->data = $data;
        
        //set id
        $this->id = substr(md5(microtime(true)),0,6);
    }
    
    /**
     * Calls a slot using a template callback
     * 
     * @param string $name
     * @param function $template
     * @return \View
     */
    function slot($name, $template)
    {
        if (!empty($this->slot[$name])) {
            foreach ($this->slot[$name] as $item) {
                $template($item);
            }
        }
        return $this;
    }
    
    /**
     * Sets up a new slot identified by name
     * 
     * @param string $name
     * @return \View
     */
    public function addSlot($name)
    {
        if (!isset($this->slot[$name])) {
            $this->slot[$name] = array();
        }
        return $this;
    }
    
    /**
     * Returns the named slots
     * @return array
     */
    public function getSlots()
    {
        return arra_keys($this->slot);
    }
    
    /**
     * Returns slot items
     * @param string $name
     * @return array
     */
    public function getSlotItems($name)
    {
        if (empty($this->slot[$name])) return array();
        return $this->slot[$name];
    }

    /**
     * Sets the slot as empty
     * @param string $name
     */
    public function emptySlot($name) {
        $this->slot[$name] = array();
    }

    /**
     * Adds content to the view
     * This can be a string, a View or a php file template path
     * 
     * You can add unique content by setting $unique to true. This will check
     * whether the content is already on the view or not
     * 
     * @param mixed $content
     * @param string $slotName
     * @param boolean $unique
     * @return \View
     */
    public function addContent($content, $slotName = 'content', $unique = false)
    {
        $skip = false;
        if (in_array($slotName, array('css', 'js'))) {
            $unique = true;
        }
        if ($unique) {
            foreach ($this->slot[$slotName] as $titem) {
                if (gettype($titem) == gettype($content) 
                        && $titem == $content) {
                    $skip = true;
                }
            }
        }
        if (!$skip) {
            if (!isset($this->slot[$slotName])) {
                $this->slot[$slotName] = array();
            }
            $this->slot[$slotName][] = $content;
        }
        return $this;
    }
    
    /**
     * Set data to the view
     * This data keys will be transform to $variables using explode()
     * The value can be anything ie. string, array, object, etc...
     * Just make sure it will be properly used in the template
     * 
     * @param string $key
     * @param mixed $value
     * @return \View
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Returns the data value identified by key
     * 
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->data[$key];
    }
    
    /**
     * Sets the path template that will be used
     * @param string $template
     * @return \View
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    
    /**
     * Hides the view
     * @return \View
     */
    public function hide()
    {
        $this->hidden = true;
        return $this;
    }
    
    /**
     * Shows the view
     * @return \View
     */
    public function show()
    {
        $this->hidden = false;
        return $this;
    }
    
    public function __toString()
    {
        if (!file_exists($this->template)) return $this->output;
        if ($this->hidden) return '';
        $this->set('_id', $this->id);
        ob_start();
        if (is_array($this->data)) extract($this->data);
        include $this->template;
        $this->output = (string) ob_get_clean();
        return $this->output;
    }
}
