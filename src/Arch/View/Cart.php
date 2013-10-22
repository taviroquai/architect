<?php

namespace Arch\View;

/**
 * View Cart
 */
class Cart extends \Arch\View
{
    
    /**
     * the cart model
     * @var Model_Cart
     */
	public $model;

	public function __construct($tmpl = null, \Arch\Model\Cart $model = null)
    {
        if ($tmpl === null) {
            $tmpl = BASE_PATH.'/theme/default/cart.php';
        }
		if ($model === null) {
            $model = new \Arch\Model\Cart();
        }
        $this->model = $model;
		parent::__construct($tmpl);

        // default checkoutUrl
        $this->set('checkoutUrl', '');
	}
    
    public function __toString()
    {
        $this->set('cart', $this->model->getCart());
        return parent::__toString();
    }
}