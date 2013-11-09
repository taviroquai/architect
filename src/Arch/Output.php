<?php

namespace Arch;

/**
 * Output class
 */
class Output
{
    
    /**
     * Holds the output type, defaults to HTTP
     * @var string
     */
    protected $type;
    
    /**
     * Holds the HTTP headers
     * @var array
     */
    protected $headers = array();
    
    /**
     * Holds the output content
     * @var string
     */
    protected $content;
    
    /**
     * Returns a new Output Object
     * 
     * @param string $content The output content
     * @param string $type The output type, defaults to HTTP
     */
    public function __construct($content = '', $type = 'HTTP')
    {
        $this->content = $content;
        $this->type = $type;
    }
    
    /**
     * Sets the output content string
     * 
     * @param string $content The content to be sent
     * @return \Arch\Output
     */
    public function setContent($content)
    {
        $this->content = (string) $content;
        return $this;
    }
    
    /**
     * Returns the output content
     * @return string
     */
    public function & getContent()
    {
        return $this->content;
    }
    
    /**
     * Sets HTTP headers to be used on HTTP type
     * @param array $headers The list of headers to be sent
     * @return \Arch\Output
     */
    public function setHeaders($headers)
    {
        $this->headers = (array) $headers;
        return $this;
    }
    
    /**
     * Returns the output headers
     * @return array
     */
    public function & getHeaders()
    {
        return $this->headers;
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
     * Adds content to be cached
     * @param string $id The cache identifier
     * @param mixed $content The content to be cached
     * @param int $expire The time to expire
     */
    public function cache($id, $content, $expire = 3600)
    {
        if (empty($expire) && defined('CACHE_EXPIRE')) {
            $expire = CACHE_EXPIRE;
        }
        if (function_exists('apc_add')) {
            apc_add($id, (string) $content, $expire);
        }
    }
    
    /**
     * Removes a cached item by ID
     * @param string $id The cache identifier
     */
    public function expire($id)
    {
        if (function_exists('apc_delete')) {
            apc_delete($id);
        }
    }
    
    /**
     * Removes all user items from cache
     */
    public function expireAll()
    {
        if (function_exists('apc_clear_cache')) {
            apc_clear_cache('user');
        }
    }
    
    /**
     * Checks whether the item is cached or not
     * @param string $id The cache identifier
     * @param string $expire The time to expire
     * @return boolean
     */
    public function isCached($id, $expire = 3600)
    {
        if (empty($expire) && defined('CACHE_EXPIRE')) {
            $expire = CACHE_EXPIRE;
        }
        if (function_exists('apc_fetch')) {
            return apc_fetch($id);
        } else {
            return false;
        }
    }
    
    /**
     * Send the output
     */
    public function send()
    {
        echo $this->content;
    }
    
    /**
     * Outputs a static file
     * @param string $filename
     */
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
        $this->cleanExit();
    }
    
    protected function cleanExit()
    {
        exit();
    }
}
