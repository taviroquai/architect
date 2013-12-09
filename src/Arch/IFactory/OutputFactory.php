<?php

namespace Arch\IFactory;

/**
 * Description of Output
 *
 * @author mafonso
 */
class OutputFactory extends \Arch\IFactory
{
    /**
     * Raw output type
     */
    const TYPE_RAW = 0;
    
    /**
     * HTTP headers output type
     */
    const TYPE_HTTP = 1;
    
    /**
     * HTTP response output type
     * includes HTTP headers
     */
    const TYPE_RESPONSE = 2;
    
    /**
     * HTTP attachment output type
     * includes HTTP headers
     */
    const TYPE_ATTACHMENT = 3;
    
    /**
     * JSON output type
     * includes HTTP headers
     */
    const TYPE_JSON = 4;

    /**
     * Returns a new CLI input
     * @return \Arch\Input
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case self::TYPE_RAW:
                return new \Arch\Output\Raw();
            case self::TYPE_HTTP:
                return new \Arch\Output\HTTP();
            case self::TYPE_ATTACHMENT:
                return new \Arch\Output\HTTP\Response\Attachment();
            case self::TYPE_JSON:
                return new \Arch\Output\HTTP\Response\JSON();
            case self::TYPE_RESPONSE:
            default:
                return new \Arch\Output\HTTP\Response();
        }
    }
    
    /**
     * Create output from API
     */
    public function createFromGlobals()
    {
        $factory = new \Arch\IFactory\OutputFactory();
        $api = php_sapi_name();
        switch ($api) {
            case 'cli':
                return $factory->fabricate(self::TYPE_RAW);
            default:
                return $factory->fabricate(self::TYPE_RESPONSE);
        }
    }
}
