<?php

namespace Arch\Model;

/**
 * Model Cart
 */
class Cart
{
    /**
     * Holds the list of currency options
     * @var array
     */
    public $currency_options;
    
    /**
     * Holds the list of shipping options
     * @var array
     */
    public $shipping_options;
    
    /**
     * Holds the list of payment options
     * @var array
     */
    public $payment_options;
    
    /**
     * Holds the list of predefined product quantity options
     * @var array
     */
    public $quantity_options;
    
    /**
     * Holds the application session
     * @var \Arch\Registry\Session
     */
    protected $session;
    
    /**
     * Returns a new shopping cart model
     * @param \Arch\Registry\Session $session
     */
    public function __construct(\Arch\Registry\ISession $session)
    {
        $this->session = $session;
        $this->currency_options = array('EUR');
        $this->shipping_options = array('Standard');
        $this->payment_options  = array('PayPal');
        $this->quantity_options  = array(0,1,2,3,4,5,6,7,8,9,10);
        
    	if (!isset($this->session->cart)) {
    		$this->session->cart = (object) array(
    			'user'      => null,
    			'items'     => array(), 
    			'total_cost'     => 0, 
    			'shipping_cost'  => 0,
    			'tax_cost'       => 0,
    			'gateway'   => null,
                'currency'  => reset($this->currency_options),
                'shipping'  => reset($this->shipping_options),
                'payment'   => reset($this->payment_options)
    			);
    	}
    }

    public function insertItem($item, $index, $quantity)
    {
        $cart = $this->session->cart;
		$cart->items[$index] = (object) array(
			'product' => $item, 
			'quantity' => $quantity);
        $this->session->cart = $cart;
        $this->getTotal();
    }

    public function getItem($index)
    {
        $item = false;
        if (isset($this->session->cart->items[$index])) {
            $item =& $this->session->cart->items[$index];
        }
        return $item;
    }

    public function updateQuantity($index, $quantity)
    {
        if (isset($this->session->cart->items[$index])) {
            if ($quantity == 0) {
                unset($this->session->cart->items[$index]);
            } else {
                $this->session->cart->items[$index]->quantity = $quantity;
            }
            $this->getTotal();
        }
    }

	public function updateTaxCost($tax)
    {
		$this->session->cart->tax_cost = $tax;
		$this->getTotal();
	}

    public function updateShippingCost($shipping)
    {
        $this->session->cart->shipping_cost = $shipping;
        $this->getTotal();
    }

    public function getTotal()
    {
        $subtotal = 0;
        $tax = 0;
        foreach ($this->session->cart->items as $item) {
            $subtotal += $item->quantity * $item->product->price;
            $tax += ($item->product->tax * $item->product->price);
        }
        if ($subtotal == 0) {
            $this->session->cart->shipping_cost = 0;
        }
        $this->session->cart->tax_cost = $tax;
        $this->session->cart->total_cost = 
            $subtotal + $tax + 
            $this->session->cart->shipping_cost;
        return $this->session->cart->total_cost;
    }

    public function setUser($user)
    {
        $this->session->cart->user = $user;
    }

	public function setShipping($shipping)
    {
		$this->session->cart->shipping = $shipping;
        $this->getTotal();
	}
    
    public function setCurrency($gateway)
    {
		$this->session->cart->gateway = $gateway;
	}

	public function setPayment($gateway)
    {
		$this->session->cart->gateway = $gateway;
	}
    
    public function addShippingOption($option)
    {
        $this->shipping_options[] = $option;
    }
    
    public function addCurrencyOption($option)
    {
        $this->currency_options[] = $option;
    }
    
    public function addPaymentOption($option)
    {
        $this->payment_options[] = $option;
    }

	public function getCart()
    {
		return $this->session->cart;
	}
}