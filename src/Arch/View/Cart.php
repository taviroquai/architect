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
     * Returns the cart model
     * @return \Arch\Model\ICart
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Sets the cart model
     * @param \Arch\Model\ICart $model
     */
    public function setModel(\Arch\Model\ICart $model)
    {
        $this->model = $model;
    }

    /**
     * Renders the cart view
     * @return string
     */
    public function __toString()
    {
        $this->set('cart', $this->getModel()->getCart());
        $this->set('currency_options', $this->getModel()->getCurrencyOptions());
        $this->set('payment_options', $this->getModel()->getPaymentOptions());
        $this->set('quantity_options', $this->getModel()->getQuantityOptions());
        $this->set('shipping_options', $this->getModel()->getShippingOptions());
        return parent::__toString();
    }
}