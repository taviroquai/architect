<?php

namespace Arch\Model;

/**
 * Interface Model Cart
 */
abstract class ICart
{
    /**
     * Holds the list of currency options
     * @var array
     */
    protected $currency_options;
    
    /**
     * Holds the list of shipping options
     * @var array
     */
    protected $shipping_options;
    
    /**
     * Holds the list of payment options
     * @var array
     */
    protected $payment_options;
    
    /**
     * Holds the list of predefined product quantity options
     * @var array
     */
    protected $quantity_options;
    
    /**
     * Holds the cart object
     * @var object
     */
    protected $cart;
    
    /**
     * Returns the currency option(s)
     * @param int $index
     * @return string|array
     */
    public function getCurrencyOptions($index = null)
    {
        if (is_integer($index)) {
            if (isset($this->currency_options[$index])) {
                return $this->currency_options[$index];
            } else {
                return reset($this->currency_options);
            }
        }
        return $this->currency_options;
    }
    
    /**
     * Returns the shipping option(s)
     * @param int $index
     * @return string|array
     */
    public function getShippingOptions($index = null)
    {
        if (is_integer($index)) {
            if (isset($this->shipping_options[$index])) {
                return $this->shipping_options[$index];
            } else {
                return reset($this->shipping_options);
            }
        }
        return $this->shipping_options;
    }
    
    /**
     * Returns the payment option(s)
     * @param int $index
     * @return string|array
     */
    public function getPaymentOptions($index = null)
    {
        if (is_integer($index)) {
            if (isset($this->payment_options[$index])) {
                return $this->payment_options[$index];
            } else {
                return reset($this->payment_options);
            }
        }
        return $this->payment_options;
    }
    
    /**
     * Returns the quantity option(s)
     * @param int $index
     * @return int
     */
    public function getQuantityOptions($index = null)
    {
        if (is_integer($index)) {
            if (isset($this->quantity_options[$index])) {
                return $this->quantity_options[$index];
            } else {
                return reset($this->quantity_options);
            }
        }
        return $this->quantity_options;
    }

    /**
     * Sets the currency options
     * @param array $options
     */
    public function setCurrencyOptions(array $options)
    {
        $this->currency_options = $options;
    }
    
    /**
     * Sets the shipping options
     * @param array $options
     */
    public function setShippingOptions(array $options)
    {
        $this->shipping_options = $options;
    }
    
    /**
     * Sets the payment options
     * @param array $options
     */
    public function setPaymentOptions(array $options)
    {
        $this->payment_options = $options;
    }
    
    /**
     * Sets the quantity options
     * @param array $options
     */
    public function setQuantityOptions(array $options)
    {
        $this->quantity_options = $options;
    }

    /**
     * Loads a cart object from session
     * @param \Arch\Registry\ISession $session
     * @return boolean
     */
    public function loadCart(\Arch\Registry\ISession $session)
    {
        if ($session->get('cart')) {
            $this->cart = $session->get('cart');
            return true;
        }
        return false;
    }
    
    /**
     * Saves a cart object into session
     * @param \Arch\Registry\ISession $session
     */
    public function saveCart(\Arch\Registry\ISession $session)
    {
        $session->set('cart', $this->cart);
    }

    /**
     * Returns the current cart object
     * @return object
     */
    public function getCart() {
        return $this->cart;
    }
}