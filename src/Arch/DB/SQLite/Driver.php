<?php
namespace Arch\DB\SQLite;

/**
 * Description of SQLite driver
 *
 * @author mafonso
 */
class Driver extends \Arch\DB\Driver
{

    /**
     * Holds the database connection
     * @var \PDO
     */
    protected $schema;
    
    /**
     * Returns a new SQLite driver
     * @param string $dbname The database name
     * @param string $host The hostname
     * @param string $user The database user
     * @param string $pass The user password
     * @param \Arch\Logger The application logger
     */
    public function __construct(
        $dbname,
        $host,
        $user,
        $pass,
        \Arch\Logger $logger
    ) {
        $this->schema = $this->createPDO(
            $host,
            $dbname,
            $user,
            $pass
        );
        $this->schema->setAttribute(
            \PDO::ATTR_ERRMODE, 
            \PDO::ERRMODE_EXCEPTION
        );
        $this->connect($host, $dbname, $user, $pass);
        $this->db_pdo->setAttribute(
            \PDO::ATTR_ERRMODE, 
            \PDO::ERRMODE_EXCEPTION
        );
        $this->logger = $logger;
    }
    
    /**
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @return string
     */
    public function getDSN($host, $database, $user, $pass = '')
    {
        return 'sqlite:'.$database;
    }

    /**
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\Table
     */
    public function createTable($tablename)
    {
        $table = new \Arch\DB\SQLite\Table($tablename, $this);
        return $table;
    }

    /**
     * Returns a list of tables
     * @return array
     */
    public function getTables()
    {
        $data = array('table', 'sqlite_sequence');
        $sql = 'SELECT DISTINCT name as name '
                . 'FROM '.$this->dbname.'.sqlite_master '
                . 'WHERE type = ? '
                . 'AND name != ?';
        $stm = $this->schema->prepare($sql);
        $this->logger->log('DB schema query: '.$stm->queryString);
        $stm->execute($data);
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Returns the column foreign key if exists
     * @param string $table_name The table name
     * @param string $column_name The column
     * @return array
     */
    public function getForeignKeys($table_name, $column_name)
    {
        $result = array();
        $sql = "PRAGMA foreign_key_list(`$table_name`)";
        $stm = $this->schema->prepare($sql);
        $this->logger->log('DB schema query: '.$stm->queryString);
        if ($stm->execute() && $rows = $stm->fetchAll(\PDO::FETCH_ASSOC)) {
            foreach ($rows as $row) {
                if ($row['from'] == $column_name) {
                    $result = $row;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Returns the table info
     * @param string $table_name The table name
     * @return array
     */
    public function getTableInfo($table_name)
    {
        $result = array();
        $sql = "PRAGMA table_info(`$table_name`)";
        $data = array($table_name);
        try {
            $stm = $this->db_pdo->prepare($sql);
            $this->logger->log('DB query: '.$stm->queryString);
            if ($stm->execute()) {
                $result = $stm->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            $this->logger->log('DB query error: '.$e->getMessage(), 'error');
        }
        return $result;
    }
    
    /**
     * Returns the relation column between two tables, if exists
     * @param string $first_table The first table name
     * @param string $second_table The related table name
     * @return string
     */
    public function getRelationColumn($first_table, $second_table)
    {
        $result = '';
        $table = $this->getTableInfo($first_table);
        foreach ($table as $item) {
            $relitem = $this->getForeignKeys($first_table, $item['name']);
            if (isset($relitem['table'])
                && $relitem['table'] == $second_table) {
                $result = $item['name'];
                break;
            }
        }
        return $result;
    }
    
}
