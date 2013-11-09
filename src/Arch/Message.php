<?php

namespace Arch;

/**
 * Class message
 */
class Message
{
    
    /**
     * Hold the message text
     * @var string
     */
	public $text;
    
    /**
     * Holds the HTML element class attribute to be used in template
     * @var string
     */
	public $cssClass;

    /**
     * Returns a new message
     * @param type $text The message text
     * @param type $cssClass The HTML element class attribute
     */
	public function __construct($text, $cssClass = 'alert alert-success')
    {
		$this->text = $text;
		$this->cssClass = $cssClass;
	}
}