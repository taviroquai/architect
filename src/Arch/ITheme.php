<?php

namespace Arch;

/**
 * Theme class
 */
abstract class ITheme extends \Arch\Registry\View
{
    /**
     * Holds the theme slots
     * @var array
     */
    protected $slot;
    
    /**
     * Returns a new Theme
     * @param string $template
     * @param array $data
     */
    public function __construct($template = '', array $data = array()) {
        parent::__construct($template, $data);
        $this->slot = array();
    }


    /**
     * Calls a slot using a template callback
     * 
     * @param string $slotName The slot name
     * @param function $template The slot template
     * @return \Arch\ITheme
     */
    function render($slotName, callable $template)
    {
        if (!empty($this->slot[$slotName])) {
            foreach ($this->slot[$slotName] as $item) {
                $template($item);
            }
        }
        return $this;
    }
    
    /**
     * Sets up a new slot identified by name
     * 
     * @param string $name The name of the new slot
     * @return \Arch\ITheme
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
        $result = array();
        if (isset($this->slot[$name])) {
            $result = $this->slot[$name];
        }
        return $result;
    }

    /**
     * Sets the slot as empty
     * @param string $name The name of the slot to be emptyed
     * @return \Arch\ITheme
     */
    public function emptySlot($name = 'content') {
        $this->slot[$name] = array();
        return $this;
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
     * @return \Arch\ITheme
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
            foreach ($this->slot[$slotName] as $item) {
                if (gettype($item) == gettype($content) && $item == $content) {
                    $skip = true;
                }
            }
        }
        if (!$skip) {
            $this->slot[$slotName][] = $content;
        }
        return $this;
    }
}
