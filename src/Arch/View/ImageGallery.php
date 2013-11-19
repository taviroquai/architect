<?php
namespace Arch\View;

/**
 * Description of ImageGallery
 *
 * @author mafonso
 */
class ImageGallery extends \Arch\View\FileExplorer {
    
    /**
     * Returns a new image gallery
     * @param string $path The local path
     * @param string $tmpl The template file
     */
    public function __construct($path, $tmpl = null) {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','imagegallery.php'));
        }
        parent::__construct($path, $tmpl);
    }
}
