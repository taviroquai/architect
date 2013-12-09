<?php
namespace Arch\DB\MySql;

/**
 * Description of MySql driver
 *
 * @author mafonso
 */
class Driver extends \Arch\DB\IDriver
{

    /**
     * Holds the database connection
     * @var \PDO
     */
    protected $schema;
    
    /**
     * Connect to MySql database
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $pass
     */
    public function connect($host, $database, $user, $pass) {
        $this->schema = $this->createPDO(
            $host,
            'information_schema',
            $user,
            $pass
        );
        $this->schema->setAttribute(
            \PDO::ATTR_ERRMODE, 
            \PDO::ERRMODE_EXCEPTION
        );
        parent::connect($host, $database, $user, $pass);
    }

        /**
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @return string
     */
    public function getDSN($host, $database, $user, $pass = '')
    {
        $items = array();
        $items[] = 'hostname='.$host;
        $items[] = 'dbname='.$database;
        $items[] = 'charset=UTF8';
        return 'mysql:'.implode(';', $items);
    }

    /**
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\Table
     */
    public function createTable($tablename)
    {
        $table = new \Arch\DB\MySql\Table($tablename, $this);
        return $table;
    }

    /**
     * Returns a list of tables
     * @return array
     */
    public function getTables()
    {
        $data = array($this->dbname);
        $sql = 'SELECT DISTINCT TABLE_NAME as name '
                . 'FROM COLUMNS '
                . 'WHERE TABLE_SCHEMA = ?';
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
        $data = array($this->dbname, $table_name, $column_name);
        $sql = 'SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME ' .
            'FROM KEY_COLUMN_USAGE ' .
            'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL';
        $stm = $this->schema->prepare($sql);
        $this->logger->log('DB schema query: '.$stm->queryString);
        if ($stm->execute($data) && $t = $stm->fetch(\PDO::FETCH_ASSOC)) {
            $result = $t;
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
        $sql = "DESCRIBE `$table_name`";
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
            $relitem = $this->getForeignKeys($first_table, $item['Field']);
            if (isset($relitem['REFERENCED_TABLE_NAME'])
                && $relitem['REFERENCED_TABLE_NAME'] == $second_table) {
                $result = $item['Field'];
                break;
            }
        }
        return $result;
    }
    
}
