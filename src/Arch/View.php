<?php

namespace Arch;

/**
 * View class
 */
class View
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
     * Holds the user data
     * @var array
     */
    protected $data;
    
    /**
     * Holds the view slots
     * @var array
     */
    protected $slot;
    
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
     * @param mixed $content The template, view or content
     * @param array $data The user data
     */
    public function __construct($content = '', $data = array())
    {
        // reset properties
        $this->slot = array('content' => array());
        $this->hidden = false;
        
        // check if $mixed is a file path
        if (file_exists($content)) {
            $this->template = $content;
        } else {
            // if not, consider it as content
            $this->output = $content;
        }
        // set default data
        $this->data = $data;
        
        //set id
        $this->id = 'a'.substr(md5(microtime(true)),0,6);
    }
    
    /**
     * Calls a slot using a template callback
     * 
     * @param string $name The slot name
     * @param function $template The slot template
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
     * @param string $name The name of the new slot
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
        return array_keys($this->slot);
    }
    
    /**
     * Returns slot items
     * @param string $name The name of the slot
     * @return array
     */
    public function getSlotItems($name = 'content')
    {
        if (empty($this->slot[$name])) return array();
        return $this->slot[$name];
    }

    /**
     * Sets the slot as empty
     * @param string $name The name of the slot to be emptyed
     */
    public function emptySlot($name = 'content') {
        $this->slot[$name] = array();
    }

    /**
     * Adds content to the view
     * This can be a string, a View or a php file template path
     * 
     * You can add unique content by setting $unique to true. This will check
     * whether the content is already on the view or not
     * 
     * @param mixed $content The template, view or content
     * @param string $slotName The name of the slot
     * @param boolean $unique Tells whether this content should be unique
     * @return \View
     */
    public function addContent($content, $slotName = 'content', $unique = false)
    {
        $skip = false;
        if (!isset($this->slot[$slotName])) {
            $this->addSlot($slotName);
        }
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
     * @param string $key The data key
     * @param mixed $value The data value
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
     * @param string $key The data key to be returned
     * @return mixed
     */
    public function get($key)
    {
        if (!isset($this->data[$key])) return false;
        return $this->data[$key];
    }
    
    /**
     * Sets the path template that will be used
     * @param string $template The template file
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
    
    /**
     * Renders the view
     * @return string
     */
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
