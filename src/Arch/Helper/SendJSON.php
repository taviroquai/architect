<?php

namespace Arch\Helper;

/**
 * Description of SendJSON
 *
 * @author mafonso
 */
class SendJSON extends \Arch\IHelper
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

    public function execute() {
        
        $factory = new \Arch\IFactory\OutputFactory();
        $this->app->output = $factory->create(
            \Arch\IFactory\OutputFactory::TYPE_JSON
        );
        $this->output->setBuffer(json_encode($this->data));
    }
}
