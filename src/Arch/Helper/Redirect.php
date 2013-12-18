<?php

namespace Arch\Helper;

/**
 * Description of Redirect
 *
 * @author mafonso
 */
class Redirect extends \Arch\IHelper
{
    protected $url;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function run() {
        $helper = $this->app->getHelperFactory();
        $input = $this->app->getInput();
        $url = $helper->createURL($input->getAction())->run();
        if ($url !== $this->url) {
            if (empty($this->url)) {
                $this->url = $this->app->getHelperFactory()
                        ->createURL('/')->run();
            }
            $output = new \Arch\Output\HTTP();
            $output->setHeaders(array('Location: '.$this->url));
            $output->send();
            $this->app->getLogger()->log('Redirecting to '.$this->url);
            $this->app->getEvents()->triggerEvent('arch.session.save');
            $this->app->getLogger()->log('Session closed');
            $this->app->getLogger()->dumpMessages();
            $this->app->getLogger()->close();
            exit();
        }
    }
}
