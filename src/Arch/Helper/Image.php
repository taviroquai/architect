<?php

namespace Arch\Helper;

/**
 * Description of Image
 *
 * @author mafonso
 */
class Image extends \Arch\IHelper
{
    protected $filename;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function run() {
        return new \Arch\Image($this->filename);
    }
}
