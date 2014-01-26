<?php

namespace Arch\Helper;

/**
 * Description of URL
 *
 * @author mafonso
 */
class URL extends \Arch\IHelper
{
    /**
     * Holds the internal action to be called
     * @var string
     */
    protected $action;
    
    /**
     * Holds the params to be passed in the URL
     * @var array
     */
    protected $params;
    
    /**
     * Holds whether it is a HTTP or HTTPS URL
     * @var boolean
     */
    protected $https;
    
    /**
     * Returns a new URL helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the internal action
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = (string) $action;
    }
    
    /**
     * Sets the params to be encoded in the url
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = (array) $params;
    }
    
    /**
     * Tells whether it is HTTPS protocol
     * @param boolean $boolean
     */
    public function setHTTPS($boolean)
    {
        $this->https = (boolean) $boolean;
    }

    /**
     * Returns the final URL
     * @return string
     */
    public function run() {
        return (string) $this;
    }
    
    /**
     * Returns the final URL
     * @return string
     */
    public function __toString() {
        $base_url = $this->app->getConfig()->get('BASE_URL');
        $index_file = $this->app->getConfig()->get('INDEX_FILE');
        $host = $this->app->getInput()->getHttpHost() ?
                $this->app->getInput()->getHttpHost() : 'localhost';
        $protocol = $this->https ? 'https://' : 'http://';
        $base = $index_file == '' ? rtrim($base_url, '/') : $base_url.'/';
        $base = $protocol . $host . $base;
        $uri = empty($this->action) ? '' : $this->action;
        $query = empty($this->params) ? '' : '?';
        $query .= http_build_query($this->params);
        return (string) $base.$index_file.$uri.$query;
    }
}
