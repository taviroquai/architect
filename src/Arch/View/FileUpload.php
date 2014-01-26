<?php

namespace Arch\View;

/**
 * View FileUpload
 */
class FileUpload extends \Arch\Registry\View
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
     * @return string|false
     */
    public function upload($file, $targetDir, $newName = '', $is_upload = true)
    {
        $result = false;
        if (
            $is_upload
            && empty($file['error'])
            && !empty($file['name'])
            && !empty($file['tmp_name'])
            && is_dir($targetDir)
            && is_writable($targetDir)
        ) {
            $name = $file['name'];
            $destination = !empty($newName) ? $targetDir.'/'.$newName
                    : $targetDir.'/'.$name;
            if (@rename($file['tmp_name'], $destination)) {
                chmod($destination, 0644);
                $result = $destination;
            }
        }
        return $result;
    }
}