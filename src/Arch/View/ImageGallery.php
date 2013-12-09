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
     * @param string $path The local path
     */
    public function __construct($path)
    {
        parent::__construct($path);
        $this->template = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','imagegallery.php'));
    }
}
