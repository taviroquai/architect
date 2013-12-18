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
        $this->run();
        $this->app->getEvents()->triggerEvent('arch.session.save');
        $this->app->getLogger()->log('Session closed');
        $this->app->getLogger()->dumpMessages();
        $this->app->getLogger()->close();
    }

    public function run() {
        $factory = new \Arch\Factory\Output();
        $output = $factory->create(\Arch::TYPE_OUTPUT_JSON);
        $output->setBuffer(json_encode($this->data));
        $this->app->setOutput($output);
        return $this->app->getOutput();
    }
}
