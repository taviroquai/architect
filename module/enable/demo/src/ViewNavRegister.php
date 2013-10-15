<?php
namespace Arch\Demo;

class ViewNavRegister extends \Arch\View
{
    public function __construct()
    {
        parent::__construct(BASE_PATH.'/theme/demo/register_navlink.php');
        
        // hide if there is a user logged in
        if (app()->session->login) {
            $this->hide();
        }
    }
}
