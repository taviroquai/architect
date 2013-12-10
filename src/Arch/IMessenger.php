<?php

namespace Arch;

/**
 * Messenger 
 */
interface IMessenger
{
    
    public function createMessage($text, $cssClass);
    
    public function addMessage(\Arch\Message $msg);

    public function getMessages();
    
    public function clearMessages();
    
}
