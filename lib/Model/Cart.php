<?php

class Model_Cart {

    public $currency_options;
    public $shipping_options;
    public $payment_options;
    public $quantity_options;
    
    public function __construct() {
        
        $this->currency_options = array('EUR');
        $this->shipping_options = array('Standard');
        $this->payment_options  = array('PayPal');
        $this->quantity_options  = array(1,2,3,4,5,6,7,8,9,10);
        
    	if (!isset(app()->session->_cart)) {
    		app()->session->_cart = (object) array(
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

	public function insertItem($item, $index, $quantity) {
        $cart = app()->session->_cart;
		$cart->items[$index] = (object) array(
			'product' => $item, 
			'quantity' => $quantity);
        app()->session->_cart = $cart;
        $this->getTotal();
	}

	public function getItem($index) {
		if (!isset(app()->session->_cart->items[$index])) return false;
		return app()->session->_cart->items[$index];
	}

    public function updateQuantity($index, $quantity) {
        if (!isset(app()->session->_cart->items[$index])) return false;
        if ($quantity == 0) {
            unset(app()->session->_cart->items[$index]);
        }
        else app()->session->_cart->items[$index]->quantity = $quantity;
        $this->getTotal();
    }

	public function updateTaxCost($tax) {
		app()->session->_cart->tax_cost = $tax;
		$this->getTotal();
	}

    public function updateShippingCost($shipping) {
        app()->session->_cart->shipping_cost = $shipping;
        $this->getTotal();
    }

    public function getTotal() {
        $subtotal = 0;
        $tax = 0;
        //var_dump(app()->session->_cart->items);
        foreach (app()->session->_cart->items as $item) {
            $subtotal += $item->quantity * $item->product->price;
            $tax += ($item->product->tax * $item->product->price);
        }
        if ($subtotal == 0) app()->session->_cart->shipping_cost = 0;
        app()->session->_cart->tax_cost = $tax;
        app()->session->_cart->total_cost = 
            $subtotal + $tax + app()->session->_cart->shipping_cost;
        return app()->session->_cart->total_cost;
    }

    public function setUser($user) {
        app()->session->_cart->user = $user;
    }

	public function setShipping($shipping) {
		app()->session->_cart->shipping = $shipping;
        $this->getTotal();
	}
    
    public function setCurrency($gateway) {
		app()->session->_cart->gateway = $gateway;
	}

	public function setPayment($gateway) {
		app()->session->_cart->gateway = $gateway;
	}
    
    public function addShippingOption($option) {
        $this->shipping_options[] = $option;
    }
    
    public function addCurrencyOption($option) {
        $this->currency_options[] = $option;
    }
    
    public function addPaymentOption($option) {
        $this->payment_options[] = $option;
    }

	public function getCart() {
		return app()->session->_cart;
	}
}