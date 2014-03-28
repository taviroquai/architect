<?php

namespace Arch\View;

/**
 * Comment form class
 */
class CommentForm extends \Arch\Theme\Layout
{
    /**
     * Returns a new comment form view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','comment.php'));
        parent::__construct($tmpl);
        
        $this->setName('');
        $this->setEmail('');
        $this->setBody('');
    }
    
    /**
     * Sets the user name
     * @param string $name The comment user name
     */
    public function setName($name)
    {
        parent::setName($name);
    }
    
    /**
     * Sets the user email used as identifier
     * @param string $email The user email
     */
    public function setEmail($email)
    {
        $this->set('email', $email);
    }
    
    /**
     * Sets the comment body
     * @param string $body The comment body
     */
    public function setBody($body)
    {
        $this->set('body', $body);
    }
}