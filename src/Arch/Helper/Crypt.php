<?php

namespace Arch\Helper;

/**
 * Description of Crypt
 *
 * @author mafonso
 */
class Crypt extends \Arch\Helper
{
    protected $string;
    protected $salt;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setString($string)
    {
        $this->string = $string;
    }
    
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function execute() {
        return (string) $this;
    }
    
    public function __toString() {
        return crypt($this->string, $this->salt);
    }
}
