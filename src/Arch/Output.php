<?php

namespace Arch;

/**
 * Output class
 */
class Output
{
    protected $type;
    protected $headers = array();
    protected $content;
    
    /**
     * Constructor
     * 
     * @param string $content
     * @param string $type // TODO
     */
    public function __construct($content = '', $type = 'HTTP')
    {
        $this->content = $content;
        $this->type = $type;
    }
    
    /**
     * Sets the output content string
     * 
     * @param string $content
     * @return \Arch\Output
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * return the output content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets HTTP headers to be used on HTTP type
     * @param array $headers
     * @return \Arch\Output
     */
    public function setHeaders($headers)
    {
        $this->headers = (array) $headers;
        return $this;
    }
    
    /**
     * Sends HTTP Headers
     */
    public function sendHeaders()
    {
        if (!empty($this->headers)) {
            foreach ($this->headers as $item) {
                header($item);
            }
        }
    }
    
    /**
     * Send the output
     */
    public function send()
    {   
        echo $this->content;
    }
    
    public function readfile($filename) {
        
        // send unknown mime type resolved by readfile
        $parts = explode('.', $filename);
        $ext = end($parts);
        switch ($ext) {
            case 'svg': 
                header("Content-type: image/svg+xml"); 
            break;
            case 'ttf': 
                header("Content-type: application/x-font-truetype");
            break;
            case 'otf': 
                header("Content-type: application/x-font-opentype");
            break;
            case 'woff':
                header("Content-type: application/font-woff");
            break;
            case 'eot': 
                header("Content-type: application/vnd.ms-fontobject");
            break;
            case 'css':
                header("Content-type: text/css");
            break;
            case 'js':
                header("Content-type: text/javascript");
            break;
        }

        // send cache headers
        $asset_cache = 300;
        $ts = gmdate("D, d M Y H:i:s", time()+$asset_cache)." GMT";
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=".$asset_cache);

        readfile($filename);
        exit();
    }
}
