<?php
namespace Arch\View;

/**
 * Description of ImageGallery
 *
 * @author mafonso
 */
class ImageGallery extends \Arch\View\FileExplorer
{    
    /**
     * Returns a new image gallery
     */
    public function __construct()
    {
        parent::__construct();
        $this->template = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','imagegallery.php'));
    }
}
