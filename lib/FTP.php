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
        app()->log('FTP connected to '.$host);
    }
    
    public function put($localFile, $remoteFile) {
        
        $result = ftp_put($this->connection, $remoteFile, $localFile, FTP_ASCII);
        if ($result) { 
            app()->log("FTP successfully uploaded $localFile");
            return true;
        } else {
            app()->log("FTP failed upload $localFile");
            return false;
        }
    }
    
    public function close() {
        ftp_close($this->connection);
        if (!is_resource($this->connection)) app()->log('FTP connection closed');
    }
}