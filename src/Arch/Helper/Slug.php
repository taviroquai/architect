<?php

namespace Arch\Helper;

/**
 * Description of Slug
 *
 * @author mafonso
 */
class Slug extends \Arch\IHelper
{
    protected $text;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setText($text)
    {
        $this->text = $text;
    }

    public function execute() {
        $slug = preg_replace('~[^\\pL\d]+~u', '-', $this->text);
        $slug = trim($slug, '-');
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = strtolower($slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        return $slug;
    }
}
