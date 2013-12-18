<?php

namespace Arch\Rule;

/**
 * IsDate class
 */
class IsDate extends \Arch\IRule
{    
    /**
     * Execute isDate
     * @return \Arch\IRule\IsDate
     */
    public function run()
    {
        $format = isset($this->params[1]) ? $this->params[1] : 'Y-m-d';
        $date = \DateTime::createFromFormat($format, $this->params[0]);
        $this->result = $date === false ? false : true;
        return $this;
    }
}