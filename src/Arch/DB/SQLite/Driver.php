<?php
namespace Arch\DB\SQLite;

/**
 * Description of SQLite driver
 *
 * @author mafonso
 */
class Driver extends \Arch\DB\IDriver
{
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
     * @return \Arch\DB\SQLite\Table
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
        if (!isset($this->cache['tables'])) {
            $data = array('table', 'sqlite_sequence');
            $sql = 'SELECT DISTINCT name as name '
                    . 'FROM '.$this->dbname.'.sqlite_master '
                    . 'WHERE type = ? '
                    . 'AND name != ?';
            $stm = $this->db_pdo->prepare($sql);
            $this->logger->log('DB schema query: '.$stm->queryString);
            $stm->execute($data);
            $this->cache['tables'] = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this->cache['tables'];
    }
    
    /**
     * Returns the column foreign key if exists
     * @param string $table_name The table name
     * @param string $column_name The column
     * @return array
     */
    public function getForeignKeys($table_name, $column_name)
    {
        if (!isset($this->cache['fk'][$table_name][$column_name])) {
            $this->cache['fk'][$table_name][$column_name] = array();
            $sql = "PRAGMA foreign_key_list(`$table_name`)";
            $stm = $this->db_pdo->prepare($sql);
            $this->logger->log('DB schema query: '.$stm->queryString);
            if ($stm->execute() && $rows = $stm->fetchAll(\PDO::FETCH_ASSOC)) {
                foreach ($rows as $row) {
                    if ($row['from'] == $column_name) {
                        $this->cache['fk'][$table_name][$column_name] = $row;
                        break;
                    }
                }
            }
        }
        return $this->cache['fk'][$table_name][$column_name];
    }

    /**
     * Returns the table info
     * @param string $table_name The table name
     * @return array
     */
    public function getTableInfo($table_name)
    {
        if (!isset($this->cache['info'][$table_name])) {
            $this->cache['info'][$table_name] = array();
            $sql = "PRAGMA table_info(`$table_name`)";
            try {
                $stm = $this->db_pdo->prepare($sql);
                $this->logger->log('DB query: '.$stm->queryString);
                if ($stm->execute()) {
                    $this->cache['info'][$table_name] = 
                            $stm->fetchAll(\PDO::FETCH_ASSOC);
                }
            } catch (\PDOException $e) {
                $this->logger->log('DB query error: '.$e->getMessage(), 'error');
            }
        }
        return $this->cache['info'][$table_name];
    }
    
    /**
     * Returns the relation column between two tables, if exists
     * @param string $first_table The first table name
     * @param string $second_table The related table name
     * @return string
     */
    public function getRelationColumn($first_table, $second_table)
    {
        if (!isset($this->cache['relation'][$first_table][$second_table])) {
            $this->cache['relation'][$first_table][$second_table] = '';
            $table = $this->getTableInfo($first_table);
            foreach ($table as $item) {
                $relitem = $this->getForeignKeys($first_table, $item['name']);
                if (isset($relitem['table'])
                    && $relitem['table'] == $second_table) {
                    $this->cache['relation'][$first_table][$second_table] = 
                            $item['name'];
                    break;
                }
            }
        }
        return $this->cache['relation'][$first_table][$second_table];
    }
    
}
