<?php

namespace Arch\Helper;

/**
 * Description of CreateImage
 *
 * @author mafonso
 */
class CreateImage extends \Arch\Helper
{
    protected $filename;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function execute() {
        return new \Arch\Image($this->filename);
    }
}
