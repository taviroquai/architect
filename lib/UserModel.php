<?php

class UserModel {
    
    protected $db;
    protected $tablename = 'user';
    
    public function __construct($db = null) {
        if (empty($db)) $db = app()->db;
        $this->db = $db;
        
        try {
            $this->db->query("SELECT 1 FROM `{$this->tablename}`");
        } catch(PDOException $e) {
            die($e);
        }
    }
    
    public function register($email, $data) {
        if (!$this->validateCreate($data)) return false;
        $user = $this->import($this->create(), $data);
        if (empty($user->id) || !empty($user->password)) $user->password = App::encrypt($user->password);
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

        $result = true & $this->validateEmail($input);
        $result = true & $this->validatePassword($input);
        $result = true & $this->validateNewEmail($input);
        return $result;
    }
    
    public function validateEmail($input) {

        $result = true;
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            app()->addMessage('Invalid email address', 'alert alert-error');
            $result = false;
        }
        return $result;
    }
    
    public function validatePassword($input) {

        $result = true;
        if (strlen($input['password']) < 6) {
            app()->addMessage('Password too weak', 'alert alert-error');
            $result = false;
        }
        if ($input['password'] != $input['password_confirm']) {
            app()->addMessage('Password does not match', 'alert alert-error');
            $result = false;
        }
        return $result;
    }
    
    public function validateNewEmail($input) {
    
        $result = true;
        $exists = $this->find('email = ?', array($input['email']));
        if (!empty($exists->id)) {
            app()->addMessage('Please use other email.', 'alert alert-error');
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
    
    public function load($id, $db = null) {
        if (empty($db)) $db =& $this->db;
        try {
            $sth = $db->prepare('select t1.* from user t1 where id = ?');
            $data = array($id);
            $r = $sth->execute($data);
            return $sth->fetchObject();
        }
        catch(PDOException $e) {
            die($e);
        }
    }
    
    public function find($where, $data, $db = null) {
        if (empty($db)) $db =& $this->db;
        try {
            $sth = $db->prepare('select t1.id, t1.email from user t1 where '.$where);
            $r = $sth->execute($data);
            return $sth->fetchObject();
        }
        catch(PDOException $e) {
            die($e);
        }
    }

    public function save($user, $db = null) {
        if (empty($db)) $db =& $this->db;
        try {
            if (empty($user->id)) {
                $sth = $db->prepare('insert into user (email,password) values (?,?)');
                $data = array($user->email, $user->password);
                $r = $sth->execute($data);
            }
            else {
                $sth = $db->prepare('update user set email = ?, password = ?)');
                $data = array($user->email, $user->password);
                $r = $sth->execute($data);
            }
            return $r;
        }
        catch(PDOException $e) {
            die($e);
        }
    }
    
    public function delete($where, $data, $db = null) {
        if (empty($db)) $db =& $this->db;
        try {
            $sth = $db->prepare('delete from user where '.$where);
            return $sth->execute($data);
        }
        catch(PDOException $e) {
            die($e);
        }
    }
}
