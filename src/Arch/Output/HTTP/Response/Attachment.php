<?php

namespace Arch\Output\HTTP\Response;

/**
 * HTTP output class
 */
class Attachment extends \Arch\Output\HTTP\Response
{
    protected $name;
    
    public function import($filename) {
        parent::import($filename);
        $this->name = basename($filename);
        return true;
    }
    
    public function send() {
        $this->headers[] = 'Content-disposition: attachment; filename='
                . $this->name;
        parent::send();
    }
}
