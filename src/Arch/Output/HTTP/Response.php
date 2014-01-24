<?php

namespace Arch\Output\HTTP;

/**
 * HTTP output class
 */
class Response extends \Arch\Output\HTTP
{
    /**
     * Maps unknown file type to response content type
     * @var array
     */
    protected $mapExtToType = array(
        'svg'   => "image/svg+xml",
        'ttf'   => "application/x-font-truetype",
        'otf'   => "application/x-font-opentype",
        'woff'  => "application/font-woff",
        'eot'   => "application/vnd.ms-fontobject",
        'css'   => "text/css",
        'js'    => "text/javascript"
    );
    
    /**
     * Send the output
     */
    public function send()
    {
        parent::send();
        echo $this->buffer;
    }
    
    /**
     * Outputs a static file
     * @param string $filename
     */
    public function import($filename) {
        
        parent::import($filename);
        
        // send unknown mime type resolved by readfile
        $parts = explode('.', $filename);
        $ext = end($parts);
        
        if (!isset($this->mapExtToType[$ext])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $filename);
        } else {
            $type = $this->mapExtToType[$ext];
        }
        $this->headers[] = "Content-type: ".$type;
        
        return true;
    }
}
