<?php

class UserModel {
    
    public function __construct() {
        
    }
    
    public function register($email, $data) {
        if (!$this->validateCreate($data)) return false;
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

        $email = $this->validateEmail($input);
        $pass = true & $this->validatePassword($input);
        $new = true & $this->validateNewEmail($input);
        $result = $email & $pass & $new;
        return $result;
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
        $sth = q('user')->where('id = ?', array($id))->select();
        return $sth->fetchObject();
    }
    
    public function find($where, $data) {
        $sth = q('user')->where($where, $data)->select();
        return $sth->fetchObject();
    }

    public function save(&$user) {
        $data = array('email' => $user->email, 'password' => $user->password);
        if (empty($user->id)) {
            $stm = q('user')->insert($data);
            $user->id = app()->db->lastInsertId();
        }
        else q('user')->where('id = ?', array($user->id))->update($data);
    }
    
    public function delete($where, $data) {
        q('user')->where($where, $data)->delete();
    }
}
