<?php

namespace Arch\Helper;

/**
 * Description of URL
 *
 * @author mafonso
 */
class URL extends \Arch\IHelper
{
    protected $action;
    protected $params;
    protected $https;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }
    
    public function setParams($params)
    {
        $this->params = (array) $params;
    }
    
    public function setHTTPS($boolean)
    {
        $this->https = $boolean;
    }

    public function run() {
        return (string) $this;
    }
    
    public function __toString() {
        $base_url = $this->app->getConfig()->get('BASE_URL');
        $index_file = $this->app->getConfig()->get('INDEX_FILE');
        $host = $this->app->getInput()->getHttpHost() ?
                'localhost' : $this->app->getInput()->getHttpHost();
        $protocol = $this->https ? 'https://' : 'http://';
        $base = $index_file == '' ? rtrim($base_url, '/') : $base_url.'/';
        $base = $protocol . $host . $base;
        $uri = empty($this->action) ? '' : $this->action;
        $query = empty($this->params) ? '' : '?';
        $query .= http_build_query($this->params);
        return $base.$index_file.$uri.$query;
    }
}
