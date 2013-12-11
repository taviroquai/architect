<?php

namespace Arch\Output\HTTP\Response;

/**
 * JSON output class
 */
class JSON extends \Arch\Output\HTTP\Response
{
    public function __construct($buffer = '') {
        parent::__construct($buffer);
        $headers = array(
            'Cache-Control: no-cache, must-revalidate',
            'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
            'Content-type: application/json; charset=utf-8'
        );
        $this->setHeaders($headers);
    }
}
