<?php

namespace Arch\Input\HTTP;

/**
 * Description of GET
 *
 * @author mafonso
 */
class GET extends \Arch\Input\HTTP
{
    /**
     * Returns a new HTTP GET Request
     */
    public function __construct()
    {
        parent::__construct('GET');
    }
}
