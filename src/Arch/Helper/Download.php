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

    /**
     * Tells whether or not the file will be downloaded
     * @return boolean
     */
    public function execute() {
        $result = true;
        $output_factory = new \Arch\IFactory\OutputFactory();
        $output = $output_factory->create(
            \Arch\IFactory\OutputFactory::TYPE_RESPONSE
        );
        if ($this->attachment) {
            $output = $output_factory->create(
                \Arch\IFactory\OutputFactory::TYPE_ATTACHMENT
            );
        }
        $this->app->setOutput($output);
        if (!is_readable($this->filename)) {
            $this->app->getLogger()->log(
                'Download failed. File not found: '.$this->filename, 'error'
            );
            $this->app->getSession()->createMessage(
                'File to download was not found',
                'alert alert-error'
            );
            $result = false;
        } else {
            $this->app->getOutput()->import($this->filename);
        }
        return $result;
    }
}
