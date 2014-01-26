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
    
    /**
     * Returns a new JSON helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the data to be encoded
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = (array) $data;
    }
    
    /**
     * Sends the output and saves session
     */
    public function send()
    {
        $this->run();
        $this->app->getEvents()->triggerEvent('arch.session.save');
        $this->app->getLogger()->log('Session closed');
        $this->app->getLogger()->dumpMessages();
        $this->app->getLogger()->close();
    }

    /**
     * Returns the output object
     * @return \Arch\IOutput
     */
    public function run() {
        $factory = new \Arch\Factory\Output();
        $output = $factory->create(\Arch::TYPE_OUTPUT_JSON);
        $output->setBuffer(json_encode($this->data));
        $this->app->setOutput($output);
        return $this->app->getOutput();
    }
}
