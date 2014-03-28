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
     * Holds fetched schema information
     * @var array
     */
    protected $cache = array();

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
     * @param \Arch\ILogger $logger
     */
    public function setLogger(\Arch\ILogger $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Sends a message to the logger
     * @param string $msg The message text
     * @param string $label The message type
     */
    public function log($msg, $label = 'access')
    {
        if ($this->getLogger()) {
            $this->getLogger()->log($msg, $label);
        }
    }
    
    /**
     * Runs an SQL file
     * This should be used by modules to install their database structure
     * 
     * @param string $filename
     * @throws Exception
     */
    public function install($filename)
    {
        try {
            
            if (!file_exists($filename)) {
                throw new \Exception('SQL file not found: '.$filename);
            }
            $sql = file_get_contents($filename);
            
            $this->getPDO()->beginTransaction();
            $this->getPDO()->exec($sql);
            $this->getPDO()->commit();
            return true;
            
        } catch (\PDOException $e) {
            $this->getPDO()->rollBack();
            $this->getLogger()->log('PDO Exception: '.$e->getMessage(), 'error');
        } catch (\Exception $e) {
            $this->getLogger()->log('Exception: '.$e->getMessage(), 'error');
        }
        return false;
    }
    
    /**
     * Bind PDO params filtered by type
     * @param \PDOStatement $stm A PDO statement
     * @param array $params The user params
     */
    public function dbBindParams(&$stm, $params = array())
    {
        $i = 1;
        foreach ($params as &$v) {
            $this->validateBindParam($v);
            // bind param
            $stm->bindParam($i, $v, $this->resolvePDOParamType($v));
            $i++;
        }
    }
    
    /**
     * Validates user param
     * @param mixed $v
     * @throws \Exception
     */
    protected function validateBindParam(&$v)
    {
        if (is_array( $v ) ) {
            throw new \Exception("DB bind param $v failed");
        } elseif (is_object( $v ) ) {
            if (get_class($v) == 'Closure') {
                $v = $v();
            } elseif (!method_exists( $v, '__toString' )) {
                throw new \Exception("DB bind param $v failed");
            } else {
                $v = (string) $v;
            }
        }
    }

    /**
     * Resolves PDO param type from a variable
     * @param mixed $v
     * @return int
     */
    protected function resolvePDOParamType($v)
    {
        // set default PARAM filter
        $type = \PDO::PARAM_STR;

        // try to find a match
        if (is_null($v)) {
            $type = \PDO::PARAM_INT;
        } elseif (is_numeric($v)) {
            if (is_integer($v)) {
                $type = \PDO::PARAM_INT;
            }
        } elseif (is_bool($v)) {
            $type = \PDO::PARAM_BOOL;
        }
        return $type;
    }

    /**
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @param string $user The user to authenticate
     * @param string $pass The password used in authentication
     * @return string
     */
    public abstract function getDSN($host, $database, $user, $pass = '');
    
    /**
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\DB\ITable
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
