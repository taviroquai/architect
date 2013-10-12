<?php

class Message {
	
	public $text;
	public $cssClass;

	public function __construct($text, $cssClass = 'alert alert-success') {
		$this->text = $text;
		$this->cssClass = $cssClass;
	}
}