<?php

namespace Arch\View;

/**
 * View datepicker
 */
class DatePicker extends \Arch\View
{
	/**
     * Returns a new date picker view
     * @param string $tmpl The template file
     */
	public function __construct($tmpl = null)
    {
        if ($tmpl === null) {
            $tmpl = implode(DIRECTORY_SEPARATOR,
                    array(ARCH_PATH,'theme','architect','datepicker.php'));
        }
		parent::__construct($tmpl);
        
        $this->set('default', date('Y-m-d'));
	}
}