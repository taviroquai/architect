<?php

namespace Arch\View;

/**
 * View Cart
 */
class Cart extends \Arch\Registry\View
{
    /**
     * the cart model
     * @var Model_Cart
     */
    public $model;

    /**
     * Returns a new cart view
     * @param string $tmpl The template file
     * @param \Arch\Model\Cart $model
     */
    public function __construct(\Arch\Model\Cart $model = null)
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','cart.php'));
	parent::__construct($tmpl);
        
        // set model
        $this->model = $model;
        
        // default checkoutUrl
        $this->set('checkoutUrl', '');
    }
    
    /**
     * Renders the cart view
     * @return string
     */
    public function __toString()
    {
        $this->set('cart', $this->model->getCart());
        $this->set('currency_options', $this->model->currency_options);
        $this->set('payment_options', $this->model->payment_options);
        $this->set('quantity_options', $this->model->quantity_options);
        $this->set('shipping_options', $this->model->shipping_options);
        return parent::__toString();
    }
}