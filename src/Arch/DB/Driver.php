<?php
namespace Arch\DB;

/**
 * Description of Database Driver abstract class
 *
 * @author mafonso
 */
abstract class Driver
{
    /**
     * Holds the application logger
     * @var \Arch\Logger
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
     * @return \PDO
     */
    public static function createPDO($host, $database, $user, $pass = '')
    {
        $dsn = self::getDSN($host, $database);
        return new \PDO($dsn, $user, $pass);
    }
    
    /**
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @return string
     */
    public static function getDSN($host, $database)
    {
        $items = array();
        $items[] = 'hostname='.$host;
        $items[] = 'dbname='.$database;
        $items[] = 'charset=UTF8';
        return 'mysql:'.implode(';', $items);
    }
    
    /**
     * Connects to the default database
     * @param type $host The database host
     * @param type $database The database name
     * @param type $user The connection user
     * @param type $pass The user password
     */
    public function connect($host, $database, $user, $pass)
    {
        $this->db_pdo = self::createPDO($host, $database, $user, $pass);
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
     * @return \Arch\Logger
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
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\DB\Table
     */
    public function createTable($tablename)
    {
        
    }

    /**
     * Returns a list of tables
     * @return array
     */
    public function getTables() {
        
    }
    
    /**
     * Returns the column foreign key if exists
     * @param string $table_name The table name
     * @param string $column_name The column
     * @return array
     */
    public function getForeignKeys($table_name, $column_name)
    {
        
    }

    /**
     * Returns the table info
     * @param string $table_name The table name
     * @return array
     */
    public function getTableInfo($table_name)
    {
        
    }
    
    /**
     * Returns the relation column between two tables, if exists
     * @param string $first_table The first table name
     * @param string $second_table The related table name
     * @return string
     */
    public function getRelationColumn($first_table, $second_table)
    {
        
    }
}
