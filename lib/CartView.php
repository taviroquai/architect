<?php

class CartView extends View {
    
    /**
     * the cart model
     * @var CartModel
     */
	public $model;

	public function __construct() {
		$this->model = new CartModel();
		parent::__construct(BASEPATH.'/theme/default/cart.php');

        // default checkoutUrl
        $this->set('checkoutUrl', '');
	}
    
    public function __toString() {
        $this->set('cart', $this->model->getCart());
        return parent::__toString();
    }
}