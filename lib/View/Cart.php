<?php

class View_Cart extends View {
    
    /**
     * the cart model
     * @var Model_Cart
     */
	public $model;

	public function __construct($tmpl = null, Model_Cart $model = null) {
        if ($tmpl === null) $tmpl = BASEPATH.'/theme/default/cart.php';
		if ($model === null) $model = new Model_Cart();
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