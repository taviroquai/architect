<?php
namespace Arch\Demo;

class ViewNavMenu extends \Arch\View\Menu
{
    public function __construct()
    {
        parent::__construct(BASE_PATH.'/theme/demo/main_menu.php');
        
        // add demo menu item
        $this->addItem('Demo', app()->url('/demo'));
    }
}
