<?php

namespace Arch\DB;

/**
 * Description of Database Driver abstract class
 *
 * @author mafonso
 */
abstract class IDriver
{
    /**
     * Holds the application logger
     * @var \Arch\ILogger
     */
    protected $logger;
    
    /**
     * Holds the default database name
     * @var string
     */
    protected $dbname;
    
    /**
     * Holds the default database PDO object
     * @var \PDO
     */
    protected $db_pdo;

    /**
     * Returns a new \PDO object
     * @param string $host The hostname
     * @param string $database The database name
     * @param string $user The username
     * @param string $pass The user password
     * @param array  $options The PDO options
     * @return \PDO
     */
    public function createPDO(
        $host,
        $database,
        $user,
        $pass = '',
        $options = array()
    ) {
        $dsn = $this->getDSN($host, $database, $user, $pass);
        $pdo = new \PDO($dsn, $user, $pass, $options);
        
        return $pdo;
    }
    
    /**
     * Connects to the default database
     * @param string $host The database host
     * @param string $database The database name
     * @param string $user The connection user
     * @param string $pass The user password
     */
    public function connect($host, $database, $user, $pass)
    {
        $this->db_pdo = $this->createPDO($host, $database, $user, $pass);
        if ($this->db_pdo) {
            $this->dbname = $database;
            $this->db_pdo->setAttribute(
                \PDO::ATTR_ERRMODE, 
                \PDO::ERRMODE_EXCEPTION
            );
        }
    }
    
    /**
     * Returns the query logger
     * @return \Arch\ILogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Returns the default database connection
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->db_pdo;
    }
    
    /**
     * Sets the query logger
     * @param \Arch\Logger $logger
     */
    public function setLogger(\Arch\ILogger $logger)
    {
        $this->logger = $logger;
    }
    
    public function log($msg, $label = 'access')
    {
        if ($this->getLogger()) {
            $this->getLogger()->log($msg, $label);
        }
    }

    /**
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @return string
     */
    public abstract function getDSN($host, $database, $user, $pass = '');
    
    /**
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\DB\Table
     */
    public abstract function createTable($tablename);

    /**
     * Returns a list of tables
     * @return array
     */
    public abstract function getTables();
    
    /**
     * Returns the column foreign key if exists
     * @param string $table_name The table name
     * @param string $column_name The column
     * @return array
     */
    public abstract function getForeignKeys($table_name, $column_name);

    /**
     * Returns the table info
     * @param string $table_name The table name
     * @return array
     */
    public abstract function getTableInfo($table_name);
    
    /**
     * Returns the relation column between two tables, if exists
     * @param string $first_table The first table name
     * @param string $second_table The related table name
     * @return string
     */
    public abstract function getRelationColumn($first_table, $second_table);
    
}
