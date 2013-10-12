<?php

class View {
    
    public $data = array();
    public $slot = array('content' => array());
    protected $path;
    protected $out;

    public function __construct($mixed = '', $data = array()) {

        // check if $mixed is a file path
        if (file_exists($mixed)) $this->path = $mixed;

        // if not, consider it as content
        else $this->out = $mixed;
        
        // set default data
        $this->data = $data;
    }
    
    function slot($name, $template) {
        if (empty($this->slot[$name])) return;
        foreach ($this->slot[$name] as $item) {
            $template($item);
        }
    }
    public function addSlot($name) {
        $this->slot[$name] = array();
        return $this;
    }
    public function addContent($item, $slotName = 'content', $unique = false) {
        $skip = false;
        if (in_array($slotName, array('css', 'js'))) $unique = true;
        if ($unique) {
            foreach ($this->slot[$slotName] as $titem) {
                if (gettype($titem) == gettype($item) && $titem == $item) $skip = true;
            }
        }
        if (!$skip) $this->slot[$slotName][] = $item;
        return $this;
    }
    public function set($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    public function get($key) {
        return $this->data[$key];
    }
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
    public function __toString() {
        if (!empty($this->out)) return $this->out;
        ob_start();
        extract($this->data);
        if (file_exists($this->path)) require_once $this->path;
        else {
            foreach ($this->slot['content'] as $item) echo $item;
        }
        $this->out = (string) ob_get_clean();
        return $this->out;
    }
}
