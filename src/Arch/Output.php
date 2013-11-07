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
        $this->content = (string) $content;
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
        //trigger core event
        \Arch\App::Instance()
                ->triggerEvent('arch.http.before.headers', $this->headers);
        
        if (!empty($this->headers)) {
            foreach ($this->headers as $item) {
                header($item);
            }
        }
    }
    
    public function cache($id, $content, $expire = 3600)
    {
        if (empty($expire) && defined('CACHE_EXPIRE')) {
            $expire = CACHE_EXPIRE;
        }
        if (function_exists('apc_add')) {
            apc_add($id, (string) $content, $expire);
        }
    }
    
    public function expire($id)
    {
        if (function_exists('apc_delete')) {
            apc_delete($id);
        }
    }
    
    public function isCached($id, $expire = 3600)
    {
        if (empty($expire) && defined('CACHE_EXPIRE')) {
            $expire = CACHE_EXPIRE;
        }
        if (function_exists('apc_exists')) {
            return apc_exists($id);
        } else {
            return false;
        }
    }
    
    public function loadFromCache($id)
    {
        if (function_exists('apc_fetch')) {
            $this->setContent(apc_fetch($id));
        }
    }
    
    /**
     * Send the output
     */
    public function send()
    {
        //trigger core event
        \Arch\App::Instance()
                ->triggerEvent('arch.output.before.send', $this->content);
        
        echo $this->content;
    }
    
    public function readfile($filename) {
        
        // clear headers
        $this->headers = array();
        
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
        }

        // send cache headers
        $asset_cache = 300;
        $ts = gmdate("D, d M Y H:i:s", time()+$asset_cache)." GMT";
        $this->headers[] = "Expires: $ts";
        $this->headers[] = "Pragma: cache";
        $this->headers[] = "Cache-Control: max-age=".$asset_cache;

        $this->sendHeaders();
        readfile($filename);
        exit();
    }
}
