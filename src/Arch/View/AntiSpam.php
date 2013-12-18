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
    
    /**
     * Returns a new AntiSpam view
     */
    public function __construct() {
        parent::__construct();
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','antispam.php'));
        $this->template = $tmpl;
    }
    
    public function setSession(\Arch\Registry\ISession $session)
    {
        $this->session = $session;
        
        if (!$this->session->get('_captcha')) {
            $this->session->set('_captcha', " ");
        }
        $this->set('code', $this->session->get('_captcha'));
    }

    /**
     * Sets the user input
     * @param \Arch\IInput $input
     */
    public function setInput(\Arch\IInput $input)
    {
        $this->input = $input;
    }

    /**
     * Validates anti spam code
     * @return boolean
     * @throws \Exception
     */
    public function validate()
    {
        if (empty($this->session)) {
            throw new \Exception('Session not set in anti spam view');
        }
        if (empty($this->input)) {
            throw new \Exception('Input not set in anti spam view');
        }
        $captcha = $this->session->get('_captcha');
        $this->session->delete('_captcha');
        $user_input = $this->input->get('_captcha');
        if ($captcha != $user_input) {
            $user_input = false;
        }
        return $user_input;
    }
}
