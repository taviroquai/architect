<?php

namespace Arch\Helper;

/**
 * Description of Download
 *
 * @author mafonso
 */
class Download extends \Arch\IHelper
{
    /**
     * The file to be downloaded
     * @var string
     */
    protected $filename;
    
    /**
     * Sets whether it should sent attachment headers or not
     * @var boolean
     */
    protected $attachment;
    
    /**
     * Creates a new download helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the filename to be downloaded
     * @param string $filename The filename to be downloaded
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    
    /**
     * Sets whether it should send attachment headers or not
     * @param string $boolean
     */
    public function asAttachment($boolean)
    {
        $this->attachment = $boolean;
    }

    /**
     * Tells whether or not the file will be downloaded
     * @return boolean
     */
    public function run() {
        $result = true;
        $output_factory = new \Arch\Factory\Output();
        $output = $output_factory->create(
            \Arch::TYPE_OUTPUT_RESPONSE
        );
        if ($this->attachment) {
            $output = $output_factory->create(
                \Arch::TYPE_OUTPUT_ATTACHMENT
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
