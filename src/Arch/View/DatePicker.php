<?php

namespace Arch\View;

/**
 * View datepicker
 */
class DatePicker extends \Arch\View
{
    /**
     * Returns a new date picker view
     */
    public function __construct()
    {
        $tmpl = implode(DIRECTORY_SEPARATOR,
                array(ARCH_PATH,'theme','datepicker.php'));
        parent::__construct($tmpl);
        
        $this->set('name', 'date1');
        $this->set('value', date('Y-m-d'));
    }
}