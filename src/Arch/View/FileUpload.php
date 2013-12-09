<?php

namespace Arch\View;

/**
 * View FileUpload
 */
class FileUpload extends \Arch\View
{
    /**
     * Returns a new file upload view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','fileupload.php'));
        parent::__construct($tmpl);
        
        $this->set('name', 'upload');
        $this->set('default_img', '');
    }
    
    /**
     * Uploads a file. Supports multi-file upload.
     * 
     * Use it as:
     * 
     * $uploadEntry = app()->input->file($index);
     * 
     * <b>
     * $newFile = app()->upload($uploadEntry, '/path/to/dir', 'newname');
     * </b>
     * 
     * Where $index is the index of the $_FILES entry
     * 
     * @param array $file File entry from app()->input->file()
     * @param string $targetDir Full target directory
     * @param string $newName New name to the uploaded file
     * @param boolean $is_upload Tells whether thi file was uploaded
     * @return boolean|string
     */
    public function upload($file, $targetDir, $newName = '', $is_upload = true)
    {
        if (!$is_upload) {
            return false;
        }
        if (!empty($file['error'])) {
            return false;
        }
        if (empty($file['name']) || empty($file['tmp_name'])) {
            return false;
        }
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            return false;
        }
        $name = $file['name'];
        if (!empty($newName)) {
            $name = $newName;
        }
        $destination = $targetDir.'/'.$name;
        if (!@rename($file['tmp_name'], $destination)) {
            return false;
        }
        chmod($destination, 0644);
        return $destination;
    }
}