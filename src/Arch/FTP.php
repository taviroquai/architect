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
    
    /**
     * Creates a new FTP connection
     * @param string $host The remote host
     * @param string $username The ftp username
     * @param string $password The ftp password
     * @return boolean The connection result
     */
    public function __construct($host="", $username="", $password="")
    {
        // set up basic connection 
        $this->connection = ftp_connect($host); 

        // login with username and password 
        $result = ftp_login($this->connection, $username, $password);
        if (!$result) {
            \Arch\App::Instance()->log(
                'FTP connect to '.$host.' failed', 
                'error'
            );
        } else {
            \Arch\App::Instance()->log('FTP connected to '.$host);
        }
    }
    
    /**
     * Uploads a file to remote FTP server
     * @param string $localFile The local file to sent
     * @param string $remoteFile The remote filename to be saved
     * @return boolean The upload result
     */
    public function put($localFile, $remoteFile)
    {
        $result = ftp_put(
            $this->connection,
            $remoteFile,
            $localFile,
            FTP_BINARY
        );
        if ($result) { 
            \Arch\App::Instance()->log("FTP successfully uploaded $localFile");
            return true;
        } else {
            \Arch\App::Instance()->log("FTP failed upload $localFile");
            return false;
        }
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
        $result = ftp_get(
            $this->connection,
            $localFile,
            $remoteFile,
            FTP_BINARY
        );
        if ($result) { 
            \Arch\App::Instance()->log("FTP successfully doenloaded $localFile");
            return true;
        } else {
            \Arch\App::Instance()->log("FTP failed to download $localFile");
            return false;
        }
    }
    
    /**
     * Closes this FTP connection
     */
    public function close()
    {
        ftp_close($this->connection);
        if (!is_resource($this->connection)) {
            \Arch\App::Instance()->log('FTP connection closed');
        }
    }
}