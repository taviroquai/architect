<?php

namespace Arch\Demo;

/**
 * User model
 */
class ModelUser
{
    /**
     * Returns a new user model
     */
    public function __construct()
    {
        
    }
    
    /**
     * Returns the new user database object
     * @param array $data The associative array contaning the user data
     * @return \stdClass The user object
     */
    public function register($data)
    {
        // validate post
        if ($this->validateCreate($data)) {
            
            $email = $data['email'];
            $view = new \Arch\View(BASE_PATH.'/theme/default/mail.php');
            $view->addContent("Thank you $email for registering!");

            $r = app()->mail($email, 'Register', $view);
            if(!$r) {
                m("Registration failed. Try again.", 'alert alert-error');
            }
            else {
                m("An email was sent to your address");
                // finally register
                $user = $this->import($this->create(), $data);
                $user->password = s($user->password);
                $this->save($user);
                return $user;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Tries to login user input
     * @param array $data
     * @return mixed
     */
    public function login($email, $password) {
        
        $validator = app()->createValidator();
        $rule = $validator->createRule('email')
                ->setErrorMessage('Invalid email address')
                ->setAction('isEmail');
        $validator->addRule($rule);
        
        if ($validator->validate()->getResult()) {
            $email      = filter_var($email);
            $password   = s(filter_var($password));
            $user = $this->find('email = ? and password = ?', array($email, $password));
            if (!$user) m('Invalid email/password', 'alert alert-error');
        }
        return $user;
    }
    
    /**
     * Removes a user from database
     * @param string $email The email identifying the user
     * @return 
     */
    public function unregister($email)
    {
        return $this->delete('email = ?', array($email));
    }
    
    /**
     * Creates a new user object
     * @return \stdClass
     */
    public function create()
    {
        $user = new \stdClass;
        $user->id = null;
        $user->email = '';
        $user->password = '';
        return $user;
    }
    
    /**
     * Validates create user data
     * @param array $input The associative array containing the user data
     * @return boolean Returns true if all data is valid, otherwise false
     */
    public function validateCreate()
    {   
        $validator = app()->createValidator();
        
        $rule = $validator->createRule('email')
                ->setErrorMessage('Invalid email address')
                ->setAction('isEmail');
        $validator->addRule($rule);
        
        $rule = $validator->createRule('email')
                ->setErrorMessage('Use other email')
                ->setAction('unique')
                ->addParam(q('demo_user')->s('email')->column(0));
        $validator->addRule($rule);
        
        $rule = $validator->createRule('password')
                ->setErrorMessage('Password cannot be empty')
                ->setAction('required');
        $validator->addRule($rule);
        
        $rule = $validator->createRule('password')
                ->setErrorMessage('Password does not match')
                ->setAction('equals')
                ->addParam('password_confirm');
        $validator->addRule($rule);

        return $validator->validate()->getResult();
    }
    
    /**
     * Imports input into user object
     * @param \stdClass $user The user object
     * @param array $post The import data
     * @return \stdClass
     */
    public function import(\stdClass $user, $post)
    {
        $user->id = empty($post['id']) ? 0 : $post['id'];
        $user->email = $post['email'];
        $user->password = $post['password'];
        return $user;
    }
    
    /**
     * Returns a user from database based on its ID
     * @param integer $id The user record ID
     * @return \stdClass
     */
    public function load($id)
    {
        $sth = q('demo_user')->s('*')->w('id = ?', array($id))->run();
        return $sth->fetchObject();
    }
    
    /**
     * Returns all users matching the $where criteria
     * @param string $where The search criteria
     * @param array $data The criteria data
     * @return array|boolean
     */
    public function find($where, $data)
    {
        $sth = q('demo_user')->s('*')->w($where, $data)->run();
        return $sth->fetchObject();
    }

    /**
     * Saves one user into the database
     * @param \stdClass $user The user object
     * @return \PDOStatement
     */
    public function save(&$user)
    {
        $data = array('email' => $user->email, 'password' => $user->password);
        if (empty($user->id)) {
            $stm = q('demo_user')->i($data)->run();
            $user->id = app()->db->lastInsertId();
        }
        else $stm = q('demo_user')->u($data)->w('id = ?', array($user->id))->run();
        return $stm;
    }
    
    /**
     * Deletes the users matching the where criteria
     * @param string $where The criteria
     * @param array $data The criteria data
     * @return \PDOStatement
     */
    public function delete($where, $data)
    {
        return q('demo_user')->d($where, $data)->run();
    }
    
    /**
     * Checks database structure for this model
     * and makes operations if needed
     */
    public static function checkDatabase($install = true)
    {
        if (!q('demo_user')->execute('select 1 from demo_user', null, '')) {
            if ($install) {
                $filename = BASE_PATH.'/module/enable/demo/db/install.sql';
                q('demo_user')->install($filename);
            }
        }
    }
}
