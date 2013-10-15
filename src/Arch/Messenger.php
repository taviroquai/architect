<?php

namespace Arch;

/**
 * Messenger 
 */
interface Messenger
{
    public function addMessage($text, $cssClass);
    
    public function getMessages();
    
    public function clearMessages();
    
}
