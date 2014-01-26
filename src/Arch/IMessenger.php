<?php

namespace Arch;

/**
 * Messenger 
 */
interface IMessenger
{
    /**
     * Creates a new message
     * @param string $text The text of the message
     * @param string $cssClass The type of message
     */
    public function createMessage($text, $cssClass);
    
    /**
     * Adds a new message
     */
    public function addMessage(\Arch\Message $msg);

    /**
     * Gets all messages
     * @return array
     */
    public function getMessages();
    
    /**
     * Clears all messages
     */
    public function clearMessages();
    
}
