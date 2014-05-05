<?php
namespace Arch\DB\PostgreSQL;

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
     * Returns a Data Source Name
     * @param string $host The hostname
     * @param string $database The database name
     * @return string
     */
    public function getDSN($host, $database, $user, $pass = '')
    {
        $items = array();
        $items[] = 'host='.$host;
        $items[] = 'dbname='.$database;
        $items[] = 'user='.$user;
        $items[] = 'password='.$pass;
        return 'pgsql:'.implode(';', $items);
    }

    /**
     * Returns a new table
     * 
     * @param string $tablename The table name
     * @return \Arch\DB\PostgreSQL\Table
     */
    public function createTable($tablename)
    {
        $table = new \Arch\DB\PostgreSQL\Table($tablename, $this);
        return $table;
    }

    /**
     * Returns a list of tables
     * @return array
     */
    public function getTables()
    {
        if (!isset($this->cache['tables'])) {
            $data = array('public');
            $sql = 'SELECT DISTINCT TABLE_NAME as name '
                    . 'FROM information_schema.table '
                    . 'WHERE TABLE_SCHEMA = ?';
            $stm = $this->db_pdo->prepare($sql);
            $this->log('DB schema query: '.$stm->queryString);
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
            $data = array($table_name, $column_name);
            $sql =  'SELECT '
                    . 'tc.constraint_name, tc.table_name, kcu.column_name, '
                    . 'ccu.table_name AS foreign_table_name, '
                    . 'ccu.column_name AS foreign_column_name '
                    . 'FROM '
                        . 'information_schema.table_constraints AS tc '
                    . 'JOIN information_schema.key_column_usage AS kcu '
                        . 'ON tc.constraint_name = kcu.constraint_name '
                    . 'JOIN information_schema.constraint_column_usage AS ccu '
                        . 'ON ccu.constraint_name = tc.constraint_name '
                    . 'WHERE constraint_type = \'FOREIGN KEY\' '
                    . 'AND tc.table_name = ? AND kcu.column_name = ?';
            $stm = $this->db_pdo->prepare($sql);
            $this->log('DB schema query: '.$stm->queryString);
            if ($stm->execute($data) && $t = $stm->fetch(\PDO::FETCH_ASSOC)) {
                $this->cache['fk'][$table_name][$column_name] = $t;
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
            $data = array($table_name);
            $sql =  "SELECT * "
                    . "FROM information_schema.columns "
                    . "WHERE table_schema = 'public' "
                    . "AND table_name = ?";
            try {
                $stm = $this->db_pdo->prepare($sql);
                $this->log('DB query: '.$stm->queryString);
                if ($stm->execute($data)) {
                    $this->cache['info'][$table_name] = 
                            $stm->fetchAll(\PDO::FETCH_ASSOC);
                }
            } catch (\PDOException $e) {
                $this->log('DB query error: '.$e->getMessage(), 'error');
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
                $relitem = $this->getForeignKeys($first_table, $item['column_name']);
                if (isset($relitem['foreign_table_name'])
                    && $relitem['foreign_table_name'] == $second_table) {
                    $this->cache['relation'][$first_table][$second_table] = 
                            $item['column_name'];
                    break;
                }
            }
        }
        return $this->cache['relation'][$first_table][$second_table];
    }
    
}
