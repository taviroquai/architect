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
        
        // demo of a comment form
        $this->addContent(app()->createCommentForm());
        
        // demo of a map
        $map = app()->createMap()
            ->set('lon', 0)->set('lat', 0)->set('zoom', 2);
        $marker = $map->model->createMarker(0, 0, 'Hello Architect!', true);
        $map->model->addMarker($marker);
        $this->addContent($map);
        
        //demo of chart line
        $chart = app()->createLineChart();
        $data = array(
            array("x" => "2011 W27", "y" => 100),
            array("x" => "2011 W28", "y" => 500)
        );
        $chart->set('data', $data)
                ->set('ykeys', array('y'))
                ->set('labels', array('Sells'));
        $this->addContent($chart);
        
        // demo of a tree view
        $treeview = app()->createTreeView();
        $root = array(
            'label' => 'root',
            'nodes' => array(
                array(
                    'label' => 'level 1',
                    'nodes' => array(
                        array(
                            'label' => 'level 1.1',
                            'nodes' => array(
                                array('label' => 'level 1.1.1')
                            )
                        ),
                        array('label' => 'level 1.2')
                    )
                )
            )
        );
        $treeview->set('tree', $root);
        $this->addContent($treeview);
        
        // demo of the file explorer
        $explorer = app()->createFileExplorer();
        $explorer->set('base', BASE_PATH.'/theme');
        $explorer->set('url', '/demo');
        $this->addContent($explorer);
        
        // demo of the file gallery
        $tmpl = BASE_PATH.'/theme/default/filegallery.php';
        $explorer = app()->createFileExplorer($tmpl);
        $explorer->set('base', BASE_PATH.'/theme/demo/img');
        $explorer->set('url', '/demo');
        $explorer->set('param', 'gal');
        $this->addContent($explorer);

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
