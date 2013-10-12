<?php

class Input {

    protected $get = array();
    protected $post = array();
    protected $files = array();
    protected $raw;
    
    public function __construct() {
        if (!empty($_GET))      $this->get = $_GET;
        if (!empty($_POST))     $this->post = $_POST;
        if (!empty($_FILES))    {
            $this->remapFiles($_FILES);
        }
        $this->raw = file_get_contents("php://input");
    }
    
    public function get($key = null) {
        if (!empty($key)) {
            if (empty($this->get[$key])) return false;
            return $this->get[$key];
        }
        return $this->get;
    }
    
    public function post($key = null) {
        if (!empty($key)) {
            if (empty($this->post[$key])) return false;
            return $this->post[$key];
        }
        return $this->post;
    }
    
    public function file($index) {
        if (empty($this->files[$index])) return false;
        if (!empty($this->files[$index]['error'])) return false;
        return $this->files[$index];
    }
    
    public function getAction($key) {
        if (empty($this->route[$key])) return false;
        return $this->route[$key];
    }
    
    private function remapFiles($files) {
        $name = key($files);
        if (key($files[$name]) === 'name') {
            $file_count = 1;
            $file_keys = array_keys($files[$name]);
            foreach ($file_keys as $key) {
                $this->files[0][$key] = $files[$name][$key];
            }
        }
        else {
            $file_count = count($files[$name]);
            $file_keys = array_keys($files[$name]);
            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $this->files[$i][$key] = $files[$name][$i][$key];
                }
            }
        }
    }
}
