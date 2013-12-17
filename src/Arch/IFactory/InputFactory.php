<?php

namespace Arch\IFactory;

/**
 * Description of Input
 *
 * @author mafonso
 */
class InputFactory extends \Arch\IFactory
{
    const TYPE_CLI = 0;
    const TYPE_GET = 1;
    const TYPE_POST = 2;

    /**
     * Returns a new CLI input
     * @param int $type One of InputFactory constants
     * @return \Arch\Input\CLI
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case self::TYPE_CLI:
                return new \Arch\Input\CLI();
            case self::TYPE_POST:
                return new \Arch\Input\HTTP\POST();
            case self::TYPE_GET:
                return new \Arch\Input\HTTP\GET();
        }
        throw new \Exception('Invalid input type');
    }
    
    /**
     * Parse global server input
     */
    public static function createFromGlobals()
    {
        $factory = new \Arch\IFactory\InputFactory();
        $api = php_sapi_name();
        $raw = file_get_contents('php://input');
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $input = $factory->create(self::TYPE_CLI);
            $input->setActionParams($_SERVER['argv']);
            $input->setRaw($raw);
            return $input;
        } else {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $input = $factory->create(self::TYPE_GET);
                    $input->parseServer($_SERVER);
                    $input->setParams($_GET);
                    return $input;
                case 'POST':
                    $input = $factory->create(self::TYPE_POST);
                    $input->parseServer($_SERVER);
                    $input->setParams(array_merge($_GET, $_POST));
                    $input->setFiles($_FILES);
                    $input->setRaw($raw);
                    return $input;
                default:
                    $input = $factory->create(self::TYPE_HTTP);
                    $input->parseServer($_SERVER);
                    $input->setParams($_REQUEST);
                    $input->setRaw($raw);
                    return $input;
            }
        }
    }
}
