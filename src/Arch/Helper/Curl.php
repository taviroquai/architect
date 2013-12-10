<?php

namespace Arch\Helper;

/**
 * Description of Curl
 *
 * @author mafonso
 */
class Curl extends \Arch\IHelper
{
    protected $url;
    protected $data;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }

    public function execute() {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if (!empty($this->data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->data));
        }
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_STDERR, $this->app->getLogger()->getHandler());

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
