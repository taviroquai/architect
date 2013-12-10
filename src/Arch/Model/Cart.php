<?php

namespace Arch\Model;

/**
 * Model Cart
 */
class Cart extends \Arch\Model\ICart
{
    /**
     * Returns a new shopping cart model
     */
    public function __construct()
    {
        $this->setCurrencyOptions(array('EUR'));
        $this->setShippingOptions(array('Standard'));
        $this->setPaymentOptions(array('PayPal'));
        $this->setQuantityOptions(array(0,1,2,3,4,5,6,7,8,9,10));
        $this->cart = (object) array(
            'user'      => null,
            'items'     => array(), 
            'total_cost'     => 0, 
            'shipping_cost'  => 0,
            'tax_cost'       => 0,
            'gateway'   => null,
            'currency'  => $this->getCurrencyOptions(0),
            'shipping'  => $this->getShippingOptions(0),
            'payment'   => $this->getPaymentOptions(0)
        );
    }

    public function insertItem($item, $index, $quantity)
    {
        $this->cart->items[$index] = (object) array(
                'product' => $item, 
                'quantity' => $quantity);
        $this->getTotal();
    }

    public function getItem($index)
    {
        $item = false;
        if (isset($this->cart->items[$index])) {
            $item =& $this->cart->items[$index];
        }
        return $item;
    }

    public function updateQuantity($index, $quantity)
    {
        if (isset($this->cart->items[$index])) {
            if ($quantity == 0) {
                unset($this->cart->items[$index]);
            } else {
                $this->cart->items[$index]->quantity = $quantity;
            }
            $this->getTotal();
        }
    }

    public function updateTaxCost($tax)
    {
        $this->cart->tax_cost = $tax;
        $this->getTotal();
    }

    public function updateShippingCost($shipping)
    {
        $this->cart->shipping_cost = $shipping;
        $this->getTotal();
    }

    public function getTotal()
    {
        $subtotal = 0;
        $tax = 0;
        foreach ($this->cart->items as $item) {
            $subtotal += $item->quantity * $item->product->price;
            $tax += ($item->product->tax * $item->product->price);
        }
        if ($subtotal == 0) {
            $this->cart->shipping_cost = 0;
        }
        $this->cart->tax_cost = $tax;
        $this->cart->total_cost = 
            $subtotal + $tax + 
            $this->cart->shipping_cost;
        return $this->cart->total_cost;
    }

    public function setShipping($shipping)
    {
	$this->cart->shipping = $shipping;
        $this->getTotal();
    }
    
    public function setCurrency($gateway)
    {
        $this->cart->gateway = $gateway;
    }

    public function setPayment($gateway)
    {
        $this->cart->gateway = $gateway;
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
}