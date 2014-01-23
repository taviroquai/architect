<?php

namespace Arch;

/**
 * Image class
 *
 * @author mafonso
 */
class Image {
    
    /**
     * Image source path (only the basename)
     * @var string
     */
    public $filename;
    
    /**
     * Creates a new image
     * @param string $filename The image file path
     */
    public function __construct($filename) {
        if  (
                !is_string($filename) || 
                empty($filename) || 
                !file_exists($filename)
            )
        {
            throw new \Exception('Invalid filename');
        }
        if (!is_array(getimagesize($filename))) {
            throw new \Exception('Invalid image');
        }
        $this->filename = $filename;
    }
    
    /**
     * Creates and saves a thumb image
     * @param string $filename The target image file path or directory
     * @param int $thumbSize The desired proportional size in px
     * @return boolean
     */
    public function saveThumb($filename, $thumbSize = 60) {

        // Get image information
        list($width, $height, $type) = getimagesize($this->filename);
        
        // Choose image type
        switch ($type) {
            case 1: $imgcreatefrom = "ImageCreateFromGIF"; break;
            case 3: $imgcreatefrom = "ImageCreateFromPNG"; break;
            default: $imgcreatefrom = "ImageCreateFromJPEG";
        }

        // Load image
        $myImage = $imgcreatefrom($this->filename) 
                or die("Error: Cannot find image!"); 

        // Find purpotion
        $biggestSide = $height;
        $cropPercent = $height > 560 ? 0.5 : $width / $height;
        if ($width > $height) {
            $biggestSide = $width;
            $cropPercent = $width > 560 ? 0.5 : $height / $width;
        }
        $cropWidth   = $biggestSide*$cropPercent; 
        $cropHeight  = $biggestSide*$cropPercent; 

        // Getting the top left coordinate
        $x = ($width-$cropWidth)/2;
        $y = ($height-$cropHeight)/2;
        
        // Create new image
        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        
        // replace alpha with color
        $white = imagecolorallocate($thumb,  255, 255, 255);
        imagefilledrectangle($thumb, 0, 0, $thumbSize, $thumbSize, $white);

        // Copy into new image
        imagecopyresampled(
            $thumb, 
            $myImage, 
            0, 
            0, 
            $x, 
            $y, 
            $thumbSize, 
            $thumbSize, 
            $cropWidth, 
            $cropHeight
        ); 

        // generate thumb name and save image
        if (is_dir($filename)) {
            $filename = rtrim($filename, '/').'/'.basename ($this->filename);
        }
        $parts = explode('.', $filename);
        switch (end($parts)) {
            case 'gif':
                $result = imagegif($thumb, $filename);
                break;
            case 'png':
                $result = imagepng($thumb, $filename, 9);
                break;
            case 'jpeg':
            case 'jpg':
            default:
                $result = imagejpeg($thumb, $filename, 90);
        }
        return $result;
    }    
}
