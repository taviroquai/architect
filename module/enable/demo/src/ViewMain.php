<?php

namespace Arch\Demo;

class ViewMain extends \Arch\View
{
    public function __construct()
    {
        parent::__construct(BASE_PATH.'/theme/demo/demo.php');
        
        // add demo stylesheet
        c(BASE_URL.'theme/demo/css/style.css', 'css');
        
        // demo of breadcrumbs
        $breadcrumbs = app()->createBreadcrumbs();
        $breadcrumbs->parseAction(app()->input->getAction());
        $this->addContent($breadcrumbs);

        // demo of carousel
        $carousel = app()->createCarousel();
        c(BASE_URL.'theme/demo/carousel/style.css', 'css');
        $carousel->addItem(
                '<img src="'.BASE_URL.'theme/demo/img/carousel1.jpg" />', 1);
        $carousel->addItem(
                '<p>Slide 2</p>', 0);
        $carousel->addItem(
                '<img src="'.BASE_URL.'theme/demo/img/carousel2.jpg"  />', 0);
        $this->addContent($carousel);

        // demo of date picker
        $this->addContent(app()->createDatepicker());

        // demo of download file
        $dl_view = new \Arch\View(BASE_PATH.'/theme/default/download.php');
        $url = u('/demo', array('dl' => '/glyphicons-halflings.png'));
        $dl_view->set('url', $url);
        $this->addContent($dl_view);

        // demo of file upload
        $this->addContent(app()->createFileupload());

        // demo of pagination
        $this->addContent(app()->createPagination());

        // demo of texarea editor
        $this->addContent(app()->createTexteditor());

        // demo of the shopping cart
        $cart = app()->createCart();
        // if you use other item attributes please extend Model_Cart, View_Cart, 
        // copy template theme/default/cart.php and change attributes
        $item = (object) array('name' => 'Product1', 'price' => 30, 'tax' => 0.21);
        $cart->model->insertItem($item, 1, 2); // inserts on id 1 and quantity 2
        $cart->model->updateQuantity(1, 3); // updates item 1 quantity to 3
        $cart->model->updateShippingCost(5); // updates shipping cost to 5
        // finally add cart to content
        $this->addContent($cart);
        
        // UI crud demo
        c(BASE_URL.'theme/demo/demo.js', 'js');
        $this->addContent(new \Arch\View('theme/demo/crud.php'));
    }
}
