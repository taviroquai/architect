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
     * Send the output
     */
    public function send()
    {
        if (!\Arch\App::Instance()->input->isCli() && !empty($this->headers)) {
            foreach ($this->headers as $item) {
                header($item);
            }
        }
        \Arch\App::Instance()->log('Sending output...');
        echo $this->content;
    }
}