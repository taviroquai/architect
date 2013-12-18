<?php

namespace Arch\Factory;

/**
 * Output factory
 * 
 * Use this factory to create a new output
 *
 * @author mafonso
 */
class Output extends \Arch\IFactory
{
    /**
     * Returns a new output
     * 
     * @return \Arch\IOutput
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case \Arch::TYPE_OUTPUT_RAW:
                return new \Arch\Output\Raw();
            case \Arch::TYPE_OUTPUT_HTTP:
                return new \Arch\Output\HTTP();
            case \Arch::TYPE_OUTPUT_ATTACHMENT:
                return new \Arch\Output\HTTP\Response\Attachment();
            case \Arch::TYPE_OUTPUT_JSON:
                return new \Arch\Output\HTTP\Response\JSON();
            case \Arch::TYPE_OUTPUT_RESPONSE:
                return new \Arch\Output\HTTP\Response();
        }
        throw new \Exception('Invalid output type');
    }
    
    /**
     * Create output from global variables and constants
     * @return \Arch\IOutput
     */
    public static function createFromGlobals()
    {
        $factory = new \Arch\Factory\Output();
        $api = php_sapi_name();
        switch ($api) {
            case 'cli':
                return $factory->create(\Arch::TYPE_OUTPUT_RAW);
            default:
                return $factory->create(\Arch::TYPE_OUTPUT_RESPONSE);
        }
    }
}
