<?php

namespace Arch\Helper;

/**
 * Description of Image
 *
 * @author mafonso
 */
class Image extends \Arch\IHelper
{
    /**
     * Holds the image filename
     * @var string
     */
    protected $filename;
    
    /**
     * Returns a new Image helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the image full filename
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = (string) $filename;
    }

    /**
     * Returns a new Image object
     * @return \Arch\Image
     */
    public function run() {
        return new \Arch\Image($this->filename);
    }
}
