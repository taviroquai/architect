<?php
namespace Arch;

/**
 * Description of Validator
 *
 * @author mafonso
 */
class Validator {
    
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
     * Holds the application input
     * @var \Arch\Input
     */
    protected $input;
    
    /**
     * Returns a new Input Validator
     * @param \Arch\Input $input
     */
    public function __construct(\Arch\Input $input) {
        $this->input = $input;
        $this->messages = array();
        $this->types = glob(__DIR__.'/Rule/*.php');
        array_walk($this->types, function(&$item) {
            $item = str_replace('.php', '', basename($item));
        });
    }
    
    /**
     * Runs all the validation rules
     * @param array $rules The list of rules to be validated
     * @return boolean
     */
    public function validate($rules)
    {
        $result = true;
        $this->messages = array();
        foreach ($rules as &$rule) {
            $rule->execute();
            $result = $rule->getResult() && $result;
            if (!$rule->getResult()) {
                $message = new \Arch\Message(
                    $rule->getErrorMessage(), 'alert alert-error'
                );
                $this->messages[] = $message;
            }
        }
        return $result;
    }
    
    /**
     * Returns a new validation rule
     * @param string $name The input param
     * @param string $type The type of rule
     * @param string $error_msg The message if invalid input
     * @return \Arch\Rule
     */
    public function createRule($name, $type, $error_msg)
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception(
                'Invalid validator rule. Only accept '
                .implode(',', $this->types)
            );
        }
        $input = $this->input->get();
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
                $this->input->sanitize($name, FILTER_SANITIZE_EMAIL);
                break;
            case 'IsImage':
                $rule = new \Arch\Rule\IsImage($name, $input);
                break;
            case 'IsInteger':
                $rule = new \Arch\Rule\IsInteger($name, $input);
                $this->input->sanitize($name, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'IsMime':
                $rule = new \Arch\Rule\IsMime($name, $input);
                break;
            case 'IsTime':
                $rule = new \Arch\Rule\IsTime($name, $input);
                break;
            case 'IsUrl':
                $rule = new \Arch\Rule\IsUrl($name, $input);
                $this->input->sanitize($name, FILTER_SANITIZE_URL);
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
        $rule->addParam($this->input->get($name));
        $rule->setErrorMessage($error_msg);
        return $rule;
    }
    
    /**
     * Returns the error messages
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
