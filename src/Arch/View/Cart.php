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

    /**
     * Returns a new cart view
     * @param string $tmpl The template file
     * @param \Arch\Model\Cart $model
     */
	public function __construct($tmpl = null, \Arch\Model\Cart $model = null)
    {
        if ($tmpl == null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','cart.php'));
        }
        $this->model = $model;
		parent::__construct($tmpl);

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
        return parent::__toString();
    }
}