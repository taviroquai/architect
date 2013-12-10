<?php

namespace Arch\Helper;

/**
 * Description of Download
 *
 * @author mafonso
 */
class Download extends \Arch\IHelper
{
    protected $filename;
    protected $attachment;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    
    public function asAttachment($boolean)
    {
        $this->attachment = $boolean;
    }

    public function execute() {
        $output_factory = new \Arch\IFactory\OutputFactory();
        if ($this->attachment) {
            $this->app->setOutput($output_factory->create(
                \Arch\IFactory\OutputFactory::TYPE_ATTACHMENT
            ));
        } else {
            $this->app->setOutput($output_factory->create(
                \Arch\IFactory\OutputFactory::TYPE_RESPONSE
            ));
        }
        if (!$this->app->getOutput()->import($this->filename)) {
            $this->app->getLogger->log(
                'Download failed. File not found: '.$this->filename, 'error'
            );
            $this->app->createMessage(
                'File to download was not found',
                'alert alert-error'
            );
            return false;
        }
        return true;
    }
}
