<?php
namespace Arch\Helper;

/**
 * Description of Validator
 *
 * @author mafonso
 */
class Validator extends \Arch\IHelper
implements \Arch\IMessenger
{    
    /**
     * Holds the available rule names
     * @var array
     */
    protected $types = array();
    
    /**
     * Holds the validation messages
     * @var array
     */
    protected $messages;
    
    /**
     * Holds the rules to be validated
     * @var array
     */
    protected $rules;
    
    /**
     * Returns a new Input Validator
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App $app) {
        parent::__construct($app);
        $this->messages = array();
        $this->types = glob(__DIR__.'/../Rule/*.php');
        array_walk($this->types, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
    }
    
    /**
     * Sets the rules to be validated
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * Validates the rules
     * @return boolean
     */
    public function run()
    {
        return $this->validate($this->rules);
    }

    /**
     * Runs all the validation rules
     * @param array $rules The list of rules to be validated
     * @return boolean
     */
    public function validate($rules)
    {
        $result = true;
        $this->clearMessages();
        foreach ($rules as &$rule) {
            $rule->run();
            $result = $rule->getResult() && $result;
            if (!$rule->getResult()) {
                $message = $this->createMessage(
                    $rule->getErrorMessage(),
                    'alert alert-error'
                );
                $this->addMessage($message);
            }
        }
        return $result;
    }
    
    /**
     * Returns a new validation rule
     * @param string $name The input param
     * @param string $type The type of rule
     * @param string $error_msg The message if invalid input
     * @return \Arch\IRule
     */
    public function createRule($name, $type, $error_msg)
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception(
                'Invalid validator rule. Only accept '
                .implode(',', $this->types)
            );
        }
        $input = $this->app->getInput()->get();
        switch ($type) {
            case 'After':
                $rule = new \Arch\Rule\After($name, $input);
                break;
            case 'Before':
                $rule = new \Arch\Rule\Before($name, $input);
                break;
            case 'Between':
                $rule = new \Arch\Rule\Between($name, $input);
                break;
            case 'Depends':
                $rule = new \Arch\Rule\Depends($name, $input);
                break;
            case 'Equals':
                $rule = new \Arch\Rule\Equals($name, $input);
                break;
            case 'IsAlphaExcept':
                $rule = new \Arch\Rule\IsAlphaExcept($name, $input);
                break;
            case 'IsAlphaNumeric':
                $rule = new \Arch\Rule\IsAlphaNumeric($name, $input);
                break;
            case 'IsDate':
                $rule = new \Arch\Rule\IsDate($name, $input);
                break;
            case 'IsEmail':
                $rule = new \Arch\Rule\IsEmail($name, $input);
                $this->app->getInput()->sanitize($name, FILTER_SANITIZE_EMAIL);
                break;
            case 'IsImage':
                $rule = new \Arch\Rule\IsImage($name, $input);
                break;
            case 'IsInteger':
                $rule = new \Arch\Rule\IsInteger($name, $input);
                $this->app->getInput()->sanitize($name, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'IsMime':
                $rule = new \Arch\Rule\IsMime($name, $input);
                break;
            case 'IsTime':
                $rule = new \Arch\Rule\IsTime($name, $input);
                break;
            case 'IsUrl':
                $rule = new \Arch\Rule\IsUrl($name, $input);
                $this->app->getInput()->sanitize($name, FILTER_SANITIZE_URL);
                break;
            case 'Matches':
                $rule = new \Arch\Rule\Matches($name, $input);
                break;
            case 'OneOf':
                $rule = new \Arch\Rule\OneOf($name, $input);
                break;
            case 'Unique':
                $rule = new \Arch\Rule\Unique($name, $input);
                break;
            default:
                $rule = new \Arch\Rule\Required($name, $input);
        }
        $rule->addParam($this->app->getInput()->get($name));
        $rule->setErrorMessage($error_msg);
        return $rule;
    }
    
    /**
     * Creates a new Message
     * @param string $text The message text
     * @param string $cssClass The css class
     * @return \Arch\Message
     */
    public function createMessage($text, $cssClass) {
        return new \Arch\Message($text, $cssClass);
    }

    /**
     * Adds a message
     * @param \Arch\Message $message The message to be added
     */
    public function addMessage(\Arch\Message $message) {
        $this->messages[] = $message;
    }

    /**
     * Returns the error messages
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * Clears the validation messages
     */
    public function clearMessages() {
        unset($this->messages);
        $this->messages = array();
    }
}
