<?php

namespace Arch\Rule;

/**
 * IsTime rule class
 */
class IsTime extends \Arch\IRule
{    
    /**
     * Execute isTime
     * @return \Arch\Rule\IsTime
     */
    public function execute()
    {
        $format = isset($this->params[1]) ? $this->params[1] : 'H:i:s';
        $date = \DateTime::createFromFormat($format, $this->params[0]);
        $this->result = $date === false ? false : true;
        return $this;
    }
}