<?php

namespace Arch\Model;

/**
 * Model Cart
 */
class Cart
{

    public $currency_options;
    public $shipping_options;
    public $payment_options;
    public $quantity_options;
    
    public function __construct()
    {
        
        $this->currency_options = array('EUR');
        $this->shipping_options = array('Standard');
        $this->payment_options  = array('PayPal');
        $this->quantity_options  = array(0,1,2,3,4,5,6,7,8,9,10);
        
    	if (!isset(\Arch\App::Instance()->session->_cart)) {
    		\Arch\App::Instance()->session->_cart = (object) array(
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
        $cart = \Arch\App::Instance()->session->_cart;
		$cart->items[$index] = (object) array(
			'product' => $item, 
			'quantity' => $quantity);
        \Arch\App::Instance()->session->_cart = $cart;
        $this->getTotal();
	}

	public function getItem($index)
    {
		if (!isset(\Arch\App::Instance()->session->_cart->items[$index])) {
            return false;
        }
		return \Arch\App::Instance()->session->_cart->items[$index];
	}

    public function updateQuantity($index, $quantity)
    {
        if (!isset(\Arch\App::Instance()->session->_cart->items[$index])) {
            return false;
        }
        if ($quantity == 0) {
            unset(\Arch\App::Instance()->session->_cart->items[$index]);
        } else {
            \Arch\App::Instance()
                ->session->_cart->items[$index]->quantity = $quantity;
        }
        $this->getTotal();
    }

	public function updateTaxCost($tax)
    {
		\Arch\App::Instance()->session->_cart->tax_cost = $tax;
		$this->getTotal();
	}

    public function updateShippingCost($shipping)
    {
        \Arch\App::Instance()->session->_cart->shipping_cost = $shipping;
        $this->getTotal();
    }

    public function getTotal()
    {
        $subtotal = 0;
        $tax = 0;
        foreach (\Arch\App::Instance()->session->_cart->items as $item) {
            $subtotal += $item->quantity * $item->product->price;
            $tax += ($item->product->tax * $item->product->price);
        }
        if ($subtotal == 0) {
            \Arch\App::Instance()->session->_cart->shipping_cost = 0;
        }
        \Arch\App::Instance()->session->_cart->tax_cost = $tax;
        \Arch\App::Instance()->session->_cart->total_cost = 
            $subtotal + $tax + 
            \Arch\App::Instance()->session->_cart->shipping_cost;
        return \Arch\App::Instance()->session->_cart->total_cost;
    }

    public function setUser($user)
    {
        \Arch\App::Instance()->session->_cart->user = $user;
    }

	public function setShipping($shipping)
    {
		\Arch\App::Instance()->session->_cart->shipping = $shipping;
        $this->getTotal();
	}
    
    public function setCurrency($gateway)
    {
		\Arch\App::Instance()->session->_cart->gateway = $gateway;
	}

	public function setPayment($gateway)
    {
		\Arch\App::Instance()->session->_cart->gateway = $gateway;
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
		return \Arch\App::Instance()->session->_cart;
	}
}