<?php

namespace Arch\Helper;

/**
 * Description of Redirect
 *
 * @author mafonso
 */
class Redirect extends \Arch\Helper
{
    protected $url;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function execute() {
        $helper = $this->app->getHelperFactory();
        $input = $this->app->getInput();
        if ($helper->url($input->getAction()) !== $this->url) {
            if (empty($this->url)) {
                $this->url = $this->app->getHelperFactory()->url('/');
            }
            $output = new \Arch\Output\HTTP();
            $output->setHeaders(array('Location: '.$this->url));
            $output->send();
            $this->app->getLogger()->log('Redirecting to '.$this->url);
            $this->app->cleanEnd();
            exit();
        }
    }
}
