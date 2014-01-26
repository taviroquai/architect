<?php

namespace Arch\Helper;

/**
 * Description of Slug
 *
 * @author mafonso
 */
class Slug extends \Arch\IHelper
{
    /**
     * Holds the text to be encoded
     * @var string
     */
    protected $text;
    
    /**
     * Returns a new Slug helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the text to be transformed
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = (string) $text;
    }

    /**
     * Returns the slug string
     * @return string
     */
    public function run() {
        $slug = preg_replace('~[^\\pL\d]+~u', '-', $this->text);
        $slug = trim($slug, '-');
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = strtolower($slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        return (string) $slug;
    }
}
