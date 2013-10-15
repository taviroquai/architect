<?php
namespace Arch\Demo;

class ViewDefault extends \Arch\View
{
    public function __construct()
    {
        parent::__construct(BASE_PATH.'/theme/demo/default.php');
    }
}
