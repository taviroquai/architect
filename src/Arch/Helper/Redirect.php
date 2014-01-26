<?php

namespace Arch\Helper;

/**
 * Description of Redirect
 *
 * @author mafonso
 */
class Redirect extends \Arch\IHelper
{
    /**
     * Holds the URL to redirect to
     * @var string
     */
    protected $url;
    
    /**
     * Returns a new Redirect helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
        $this->url = (string) $this->app->getHelperFactory()->createURL('/');
    }
    
    /**
     * Sets the URL to redirect to
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string) $url;
    }

    /**
     * Creates and sends new HTTP output with location header
     */
    public function run() {
        $helper = $this->app->getHelperFactory();
        $input = $this->app->getInput();
        $url = $helper->createURL($input->getAction())->run();
        if ($url !== $this->url) {
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
