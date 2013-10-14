<?php

class UserModel {
    
    public function __construct() {
        
    }
    
    public function register($data) {
        $user = $this->import($this->create(), $data);
        $user->password = s($user->password);
        $this->save($user);
        return $user;
    }
    
    public function unregister($email) {
        return $this->delete('email = ?', array($email));
    }
    
    public function create() {
        $user = new stdClass;
        $user->id = null;
        $user->email = '';
        $user->password = '';
        return $user;
    }
    
    public function validateCreate($input) {

        $email  = $this->validateEmail($input);
        $pass   = $this->validatePassword($input);
        $new    = $this->validateNewEmail($input);
        return $email & $pass & $new;
    }
    
    public function validateEmail($input) {

        $result = true;
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            m('Invalid email address', 'alert alert-error');
            $result = false;
        }
        return $result;
    }
    
    public function validatePassword($input) {

        $result = true;
        if (strlen($input['password']) < 6) {
            m('Password too weak', 'alert alert-error');
            $result = false;
        }
        if ($input['password'] != $input['password_confirm']) {
            m('Password does not match', 'alert alert-error');
            $result = false;
        }
        return $result;
    }
    
    public function validateNewEmail($input) {
    
        $result = true;
        $exists = $this->find('email = ?', array($input['email']));
        if (!empty($exists->id)) {
            m('Please use other email.', 'alert alert-error');
            $result = false;
        }
        return $result;
    }
    
    public function import($user, $post) {
        $user->id = empty($post['id']) ? 0 : $post['id'];
        $user->email = $post['email'];
        $user->password = $post['password'];
        return $user;
    }
    
    public function load($id) {
        $sth = q('user')->w('id = ?', array($id))->s();
        return $sth->fetchObject();
    }
    
    public function find($where, $data) {
        $sth = q('user')->w($where, $data)->s();
        return $sth->fetchObject();
    }

    public function save(&$user) {
        $data = array('email' => $user->email, 'password' => $user->password);
        if (empty($user->id)) {
            q('user')->i($data);
            $user->id = app()->db->lastInsertId();
        }
        else q('user')->w('id = ?', array($user->id))->u($data);
    }
    
    public function delete($where, $data) {
        q('user')->d($where, $data);
    }
}
