<?php

namespace Arch\Helper;

/**
 * Description of JSON
 *
 * @author mafonso
 */
class JSON extends \Arch\IHelper
{
    /**
     * Sets the output data
     * @var array
     */
    protected $data = array();
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function send()
    {
        $this->execute();
        $this->app->getEvents()->triggerEvent('arch.session.save');
        $this->app->getLogger()->log('Session closed');
        $this->app->getLogger()->dumpMessages();
        $this->app->getLogger()->close();
    }

    public function execute() {
        $factory = new \Arch\IFactory\OutputFactory();
        $output = $factory->create(
            \Arch\IFactory\OutputFactory::TYPE_JSON
        );
        $output->setBuffer(json_encode($this->data));
        $this->app->setOutput($output);
        return $this->app->getOutput();
    }
}
