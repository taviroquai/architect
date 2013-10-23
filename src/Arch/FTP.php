<?php

namespace Arch;

/**
 * Class ftp
 */
class FTP
{
    /**
     * The FTP connection handler
     * @var resource
     */
    protected $connection;
    
    protected $host, $username, $password;
    
    /**
     * Creates a new FTP connection
     * @param string $host The remote host
     * @param string $username The ftp username
     * @return boolean The connection result
     */
    public function __construct($host="", $username="")
    {
        
        //setup host
        $this->host = $host;
        
        // setup username
        $this->username = $username;
    }
    
    public function connect($password)
    {
        // set up basic connection 
        $this->connection = ftp_connect($this->host);

        // login with username and password 
        return ftp_login($this->connection, $this->username, $password);
    }
    
    /**
     * Uploads a file to remote FTP server
     * @param string $localFile The local file to sent
     * @param string $remoteFile The remote filename to be saved
     * @return boolean The upload result
     */
    public function put($localFile, $remoteFile)
    {
        return ftp_put($this->connection, $remoteFile, $localFile, FTP_BINARY);
    }

    /**
     * Downloads a remote file from FTP server
     * @param string $remoteFile The remote file path
     * @param string $localFile The local file path
     * @return boolean The download result
     */
    public function get($remoteFile, $localFile)
    {
        // try to download $server_file and save to $local_file
        return ftp_get(
            $this->connection,
            $localFile,
            $remoteFile,
            FTP_BINARY
        );
    }
    
    /**
     * Closes this FTP connection
     */
    public function close()
    {
        return ftp_close($this->connection);
    }
}