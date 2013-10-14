<?php

class DemoView extends View {

    public function __construct() {
        parent::__construct(BASEPATH.'/theme/demo/demo.php');
        
        // demo of breadcrumbs
        $breadcrumbs = app()->createBreadcrumbs();
        $breadcrumbs->parseAction(app()->input->getAction());
        $this->addContent($breadcrumbs);

        // demo of carousel
        $carousel = app()->createCarousel();
        app()->addContent(BASEURL.'theme/demo/carousel/style.css', 'css');
        $carousel->addItem('<img src="'.BASEURL.'theme/demo/img/carousel1.jpg" style="width: 780px; height: 250px;" />', 1);
        $carousel->addItem('<p>Slide 2</p>', 0);
        $carousel->addItem('<img src="'.BASEURL.'theme/demo/img/carousel2.jpg" style="width: 780px; height: 250px;" />', 0);
        $this->addContent($carousel);

        // demo of date picker
        $this->addContent(app()->createDatepicker());

        // demo of download file
        $url = app()->url('/demo', array('dl' => '/glyphicons-halflings.png'));
        $this->addContent(
        '<h3>Download attachment Demo</h3><a href="'.$url.'">Download</a>');

        // demo of file upload
        $this->addContent(app()->createFileupload());

        // demo of pagination
        $this->addContent(app()->createPagination());

        // demo of texarea editor
        $this->addContent(app()->createTexteditor());

        // demo of the shopping cart
        $cart = app()->createCart();
        // if you use other item attributes please extend Model_Cart, View_Cart, copy 
        // template theme/default/cart.php and change attributes
        $item = (object) array('name' => 'Product1', 'price' => 30, 'tax' => 0.21);
        $cart->model->insertItem($item, 1, 2); // inserts on id 1 and quantity 2
        $cart->model->updateQuantity(1, 3); // updates item 1 quantity to 3
        $cart->model->updateShippingCost(5); // updates shipping cost to 5
        // finally add cart to content
        $this->addContent($cart);
    }
}
