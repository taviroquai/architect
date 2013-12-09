<?php

namespace Arch\Output\HTTP\Response;

/**
 * JSON output class
 */
class JSON extends \Arch\Output\HTTP\Response
{
    public function send() {
        $this->headers[] = 'Cache-Control: no-cache, must-revalidate';
        $this->headers[] = 'Expires: Mon, 26 Jul 1997 05:00:00 GMT';
        $this->headers[] = 'Content-type: application/json; charset=utf-8';
        parent::send();
    }
}
