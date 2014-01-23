<?php

namespace Arch\Factory;

/**
 * Description of Input factory
 * 
 * Use this to create a new input
 *
 * @author mafonso
 */
class Input extends \Arch\IFactory
{
    /**
     * Returns a new CLI input
     * @param int $type One of InputFactory constants
     * @return \Arch\IInput
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case \Arch::TYPE_INPUT_CLI:
                return new \Arch\Input\CLI();
            case \Arch::TYPE_INPUT_POST:
                return new \Arch\Input\HTTP\POST();
            case \Arch::TYPE_INPUT_GET:
                return new \Arch\Input\HTTP\GET();
        }
        throw new \Exception(
            'Invalid input type. Use one of \Arch::TYPE_INPUT'
        );
    }
    
    /**
     * Parse global server input
     * @return \Arch\IInput
     */
    public static function createFromGlobals()
    {
        $factory = new \Arch\Factory\Input();
        $raw = file_get_contents('php://input');
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $input = $factory->create(\Arch::TYPE_INPUT_CLI);
            $input->setActionParams($_SERVER['argv']);
            $input->setRaw($raw);
            return $input;
        } else {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    $input = $factory->create(\Arch::TYPE_INPUT_GET);
                    $input->parseServer($_SERVER);
                    $input->setParams($_GET);
                    return $input;
                case 'POST':
                    $input = $factory->create(\Arch::TYPE_INPUT_POST);
                    $input->parseServer($_SERVER);
                    $input->setParams(array_merge($_GET, $_POST));
                    $input->setFiles($_FILES);
                    $input->setRaw($raw);
                    return $input;
                default:
                    $input = $factory->create(\Arch::TYPE_INPUT_HTTP);
                    $input->parseServer($_SERVER);
                    $input->setParams($_REQUEST);
                    $input->setRaw($raw);
                    return $input;
            }
        }
    }
}
