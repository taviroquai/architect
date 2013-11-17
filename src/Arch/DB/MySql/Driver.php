<?php
namespace Arch\DB\MySql;

/**
 * Description of MySql driver
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
     * Returns a new MySql driver
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
        $this->schema = self::createPDO(
            $host,
            'information_schema',
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
        $sql = 'SELECT DISTINCT TABLE_NAME as name ' .
            'FROM COLUMNS ' .
            'WHERE TABLE_SCHEMA = ?';
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
        $data = array($this->dbname, $table_name, $column_name);
        $sql = 'SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME ' .
            'FROM KEY_COLUMN_USAGE ' .
            'WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL';
        $stm = $this->schema->prepare($sql);
        $this->logger->log('DB schema query: '.$stm->queryString);
        if ($stm->execute($data)) {
            return $stm->fetch(\PDO::FETCH_ASSOC);
        }
        return array();
    }

    /**
     * Returns the table info
     * @param string $table_name The table name
     * @return array
     */
    public function getTableInfo($table_name)
    {
        $sql = "DESCRIBE `$table_name`";
        $stm = $this->db_pdo->prepare($sql);
        $this->logger->log('DB query: '.$stm->queryString);
        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        return array();
    }
    
    /**
     * Returns the relation column between two tables, if exists
     * @param string $first_table The first table name
     * @param string $second_table The related table name
     * @return string
     */
    public function getRelationColumn($first_table, $second_table)
    {
        $table = $this->getTableInfo($first_table);
        foreach ($table as $item) {
            $relitem = $this->getForeignKeys($first_table, $item['Field']);
            if (isset($relitem['REFERENCED_TABLE_NAME'])
                && $relitem['REFERENCED_TABLE_NAME'] == $second_table) {
                return $item['Field'];
            }
        }
        return '';
    }
    
}
