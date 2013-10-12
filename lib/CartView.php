<?php

class CartView extends View {
    
    /**
     * the cart model
     * @var CartModel
     */
	public $model;

	public function __construct($tmpl = null, CartModel $model = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/cart.php';
		if ($model === null) $model = new CartModel();
        $this->model = $model;
		parent::__construct($tmpl);

        // default checkoutUrl
        $this->set('checkoutUrl', '');
	}
    
    public function __toString() {
        $this->set('cart', $this->model->getCart());
        return parent::__toString();
    }
}