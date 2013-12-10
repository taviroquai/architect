<?php

namespace Arch\View;

/**
 * Description of AntiSpam
 *
 * @author mafonso
 */
class AntiSpam extends \Arch\Registry\View
{
    /**
     * Holds the application session
     * @var \Arch\Registry\Session
     */
    protected $session;
    
    /**
     * Holds the user input
     * @var \Arch\Input
     */
    protected $input;
    
    public function __construct(
        \Arch\Registry\ISession $session,
        \Arch\IInput $input
    ) {
        parent::__construct();
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','antispam.php'));
        $this->template = $tmpl;
        
        $this->session = $session;
        $this->input = $input;
        
        if (!$this->session->get('_captcha')) {
            $this->session->set('_captcha', " ");
        }
        $this->set('code', $this->session->get('_captcha'));
    }
    
    public function validate()
    {
        $captcha = $this->session->get('_captcha');
        $this->session->delete('_captcha');
        $user_input = $this->input->get('_captcha');
        if ($captcha != $user_input) {
            $user_input = false;
        }
        return $user_input;
    }
}
