<?php

namespace Arch\View;

/**
 * View datepicker
 */
class DatePicker extends \Arch\Registry\View
{
    /**
     * Returns a new date picker view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','datepicker.php'));
        parent::__construct($tmpl);
        
        $this->setName('date1');
        $this->setValue(date('Y-m-d'));
    }
}
