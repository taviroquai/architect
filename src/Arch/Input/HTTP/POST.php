<?php

namespace Arch\Input\HTTP;

/**
 * Description of POST
 *
 * @author mafonso
 */
class POST extends \Arch\Input\HTTP
{
    /**
     * Holds the list of uploaded files
     * @var array
     */
    protected $files;
    
    /**
     * Returns a new HTTP POST Request
     */
    public function __construct()
    {
        parent::__construct('POST');
        $this->files = array();
    }
    
    /**
     * Return an entry from $_FILES
     * 
     * Example:
     * file(0) will return the first file uploaded result
     * 
     * @param int $index
     * @return array
     */
    public function getFileByIndex($index)
    {
        $result = false;
        if (!empty($this->files[$index])) {
            $result = $this->files[$index];
        }
        return $result;
    }
    
    /**
     * Loads HTTP FILES information
     * @param array $array
     */
    public function setFiles($array)
    {
        if (!empty($array)) {
            $this->remapFiles($array);
        }
    }
    
    /**
     * Remap uploaded files array as $files[index]['property']
     * for single and multiple files upload
     * @param array $files
     */
    private function remapFiles($files)
    {
        $this->files = array();
        $tfiles = reset($files);
        if (is_array($tfiles['name'])) {
            $this->remapMultipleFiles($files);
        } else {
            $file_keys = array_keys($tfiles);
            foreach ($file_keys as $key) {
                $this->files[0][$key] = $tfiles[$key];
            }
        }
    }
    
    /**
     * Remap uploaded files array as $files[index]['property']
     * multiple files upload
     * @param array $files
     */
    private function remapMultipleFiles($files)
    {
        $tfiles = reset($files);
        $new = array();
        foreach( $tfiles as $key => $all ){
            foreach( $all as $i => $val ){
                $new[$i][$key] = $val;    
            }    
        }
        $this->files = $new;
    }
}
