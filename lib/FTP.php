<?php

class FTP {
    
    protected $connection;
    
    public function __construct($host="", $username="", $password="")
    {
        // set up basic connection 
        $this->connection = ftp_connect($host); 

        // login with username and password 
        $result = ftp_login($this->connection, $username, $password);
        if (!$result) return false;
    }
    
    public function put($localFile, $remoteFile, $message = true) {
        
        $result = ftp_put($this->connection, $remoteFile, $localFile, FTP_ASCII);
        if ($result) { 
            if ($message) app()->addMessage("Successfully uploaded $localFile");
            return true;
        } else {
            if ($message) app()->addMessage("There was a problem while uploading $localFile");
            return false;
        }
    }
    
    public function close() {
        ftp_close($this->connection); 
    }
}