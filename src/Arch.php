<?php

/**
 * Architect constants
 *
 * @author mafonso
 */
abstract class Arch
{    
    /**
     * MySql database type
     * 
     * Use this type in the \Arch\Factory\Database factory
     */
    const TYPE_DATABASE_MYSQL = 0;
    
    /**
     * SQLite database type
     * 
     * Use this type in the \Arch\Factory\Database factory
     */
    const TYPE_DATABASE_SQLITE = 1;
    
    /**
     * PostgreSQL type
     * 
     * Use this type in the \Arch\Factory\Database factory
     */
    const TYPE_DATABASE_PGSQL = 2;
    
    /**
     * CLI input type
     * 
     * Use this in the \Arch\Factory\Input factory
     */
    const TYPE_INPUT_CLI = 0;
    
    /**
     * HTTP GET input type
     * 
     * Use this in the \Arch\Factory\Input factory
     */
    const TYPE_INPUT_GET = 1;
    
    /**
     * HTTP GET input type
     * 
     * Use this in the \Arch\Factory\Input factory
     */
    const TYPE_INPUT_POST = 2;
    
    /**
     * Raw output type
     * 
     * Use this in the \Arch\Factory\Output factory
     */
    const TYPE_OUTPUT_RAW = 0;
    
    /**
     * HTTP headers (only) output type
     * 
     * Use this in the \Arch\Factory\Output factory
     */
    const TYPE_OUTPUT_HTTP = 1;
    
    /**
     * HTTP response output type - includes HTTP headers
     * 
     * Use this in the \Arch\Factory\Output factory
     */
    const TYPE_OUTPUT_RESPONSE = 2;
    
    /**
     * HTTP attachment output type
     * 
     * Use this in the \Arch\Factory\Output factory
     */
    const TYPE_OUTPUT_ATTACHMENT = 3;
    
    /**
     * JSON output type - includes HTTP headers
     * 
     * Use this in the \Arch\Factory\Output factory
     */
    const TYPE_OUTPUT_JSON = 4;
}

/**
 * App alias. Sets or gets the global $arch application
 * 
 * @return \Arch\App The application main gate
 */
function app(\Arch\App $app = null)
{ 
    if ($app) {
        $GLOBALS['arch'] = $app;
    }
    if (
        !isset($GLOBALS['arch'])
        || get_class($GLOBALS['arch']) !== 'Arch\App'
    ) {
        throw new Exception('Please define $arch = new \Arch\App() first');
    }
    return $GLOBALS['arch'];
}