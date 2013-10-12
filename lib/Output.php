<?php

class Output {

    protected $type;
    protected $headers = array();
    protected $content;
    
    public function __construct($content = 'Hello World!', $type = 'HTTP') {
        $this->content = $content;
        $this->type = $type;
    }
    
    public function setContent($content) {
        $this->content = $content;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function setHeaders($headers) {
        $this->headers = (array) $headers;
    }
    
    public function send() {
        if (!empty($this->headers)) {
            foreach ($this->headers as $item) header($item);
        }
        echo $this->content;
    }
    
}
