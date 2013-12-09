<?php

namespace Arch\Output\HTTP;

/**
 * HTTP output class
 */
class Response extends \Arch\Output\HTTP
{
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
        switch ($ext) {
            case 'svg': 
                $this->headers[] = "Content-type: image/svg+xml";
            break;
            case 'ttf': 
                $this->headers[] = "Content-type: application/x-font-truetype";
            break;
            case 'otf': 
                $this->headers[] = "Content-type: application/x-font-opentype";
            break;
            case 'woff':
                $this->headers[] = "Content-type: application/font-woff";
            break;
            case 'eot': 
                $this->headers[] = "Content-type: application/vnd.ms-fontobject";
            break;
            case 'css':
                $this->headers[] = "Content-type: text/css";
            break;
            case 'js':
                $this->headers[] = "Content-type: text/javascript";
            break;
            default:
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $type = finfo_file($finfo, $filename);
                $this->headers[] = "Content-type: ".$type;
        }
        return true;
    }
}
