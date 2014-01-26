<?php

namespace Arch\DB;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 * TODO: separate in varous drivers (MySql, PostgreSQL, SQLite)
 */
abstract class ITable
{
    /**
     * Holds the ralational table name
     * @var string
     */
    protected $name;
    
    /**
     * Holds the database name which it belongs
     * @var string
     */
    protected $dbname;
    
    /**
     * Holds the PDO instance
     * @var \Arch\DB\Driver
     */
    protected $driver;
    
    /**
     * Holds the current statement
     * @var \PDOStatement
     */
    protected $stm;
    
    /**
     * Holds the final SQL
     * @var string
     */
    protected $sql;
    
    /**
     * The root node
     * @var \stdClass
     */
    protected $node;
    
    /**
     * Returns a new Table to start querying
     * All these queries are simple and only affect 1 table based on the name
     * For more complex queries, use PDO directly
     * 
     * @param string $name The tablename
     * @param \Arch\DB\IDriver $driver The PDO database handler to query
     */
    public function __construct($name, \Arch\DB\IDriver $driver)
    {
        if  (!is_string($name) || empty($name)) {
            throw new \Exception('Invalid table name');
        }
        $this->name = $name;
        $this->driver = $driver;
        $this->node = $this->createSelect();
    }

    /**
     * Select alias
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \Arch\DB\ITable This object
     */
    public function s($fields = '*')
    {
        return $this->select($fields);
    }
    
    /**
     * Insert alias
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing fields and values
     * @return \Arch\DB\ITable This object
     */
    public function i($values = array())
    {
        return $this->insert($values);
    }
    
    /**
     * Update alias
     * Set update values and executes an update operation
     * @param array $values An associative array containing fields and values
     * @return \Arch\DB\ITable This object
     */
    public function u($values = array())
    {
        return $this->update($values);
    }
    
    /**
     * Delete alias
     * Executes a delete operation with where condition
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Arch\DB\ITable This object
     */
    public function d($condition = '', $data = array())
    {
        return $this->delete($condition, $data);
    }
    
    /**
     * Where alias
     * Set string condition and array params
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Arch\DB\ITable This object
     */
    public function w($condition, $data = array())
    {
        return $this->where($condition, $data);
    }
    
    /**
     * Join alias
     * @param string $tablename The tablename to join
     * @param string $on The join condition
     * @param string $type The join type (LEFT, INNER, RIGHT, empty)
     * @return \Arch\DB\ITable This object
     */
    public function j($tablename, $on, $type = 'LEFT') {
        return $this->join($tablename, $on, $type);
    }
    
    /**
     * Limit alias
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Arch\DB\ITable
     */
    public function l($limit = null, $offset = 0)
    {
        return $this->limit($limit, $offset);
    }
    
    /**
     * Set group by fields
     * @param string $groupby The list of fields to group
     * @return \Arch\DB\ITable This object
     */
    public function g($groupby)
    {
        return $this->groupby($groupby);
    }

        /**
     * Execute alias
     * @param string $sql The sql to execute (optional), build if empty
     * @param array $params The SQL params
     * @return \PDOStatement
     */
    public function run($sql = '', $params = array())
    {
        return $this->execute($sql, $params);
    }
    
    /**
     * Runs the query and returns the first record
     * @param int $type The type of rows (array or class)
     * @return array
     */
    public function fetch($type = \PDO::FETCH_ASSOC)
    {
        $stm = $this->execute();
        if ($stm) {
            return $stm->fetch($type);
        }
        return array();
    }
    
    /**
     * Runs the query and returns all records
     * @param int $type The type of rows (array or class)
     * @return array
     */
    public function fetchAll($type = \PDO::FETCH_OBJ)
    {
        $stm = $this->execute();
        if ($stm) {
            return $stm->fetchAll($type);
        }
        return array();
    }
    
    /**
     * Runs the query and returns the first object
     * @return stdClass
     */
    public function fetchObject()
    {
        $stm = $this->execute();
        if ($stm) {
            return $stm->fetchObject();
        }
        return false;
    }

        /**
     * Returns a list of data of the given column
     * @param int $column The number of the column to be returned
     * @return array
     */
    public function fetchColumn($column = 0)
    {
        $stm = $this->execute();
        if ($stm) {
            $data = array();
            while ($row = $stm->fetchColumn($column)) {
                $data[] = $row;
            }
            return $data;
        }
        return array();
    }
    
    /**
     * Runs the query and returns the last insert id
     * @param string $name The auto-increment field name
     * @return boolean
     */
    public function getInsertId($name = 'id')
    {
        $stm = $this->execute();
        if ($stm) {
            return $this->driver->getPDO()->lastInsertId($name);
        }
        return false;
    }
    
    /**
     * Runs the query and returns the number of affected rows
     * @return boolean|integer
     */
    public function getRowCount()
    {
        /**
         * @var $stm PDOStatement
         */
        $stm = $this->execute();
        if ($stm) {
            return $stm->rowCount();
        }
        return false;
    }
    
    /**
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \Arch\DB\ITable This object
     */
    public function select($fields = '*')
    {
        $this->node = $this->createSelect();
        $this->node->from = $this->name;
        if (is_array($fields)) $this->node->fields = $fields;
        else $this->node->fields = $fields;
        return $this;
    }
    
    /**
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing fields and values
     * @return \Table This object
     */
    public function insert($values = array())
    {
        $this->node = $this->createInsert();
        $this->node->fields = array_keys($values);
        $this->node->values = array_values($values);
        return $this;
    }
    
    /**
     * Set update values (associative array) and executes an update operation
     * @param array $values An associative array containing fields and values
     * @return \Arch\DB\ITable This object
     */
    public function update($values = array())
    {
        $this->node = $this->createUpdate();
        $this->node->table = $this->name;
        $this->node->set = $values;
        return $this;
    }
    
    /**
     * Executes a delete operation with where condition
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Arch\DB\ITable This object
     */
    public function delete($condition = '', $data = array())
    {
        $this->node = $this->createDelete();
        $this->node->table = $this->name;
        return $this->where($condition, $data);
    }
    
    /**
     * Set string condition and array params
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Arch\DB\ITable This object
     */
    public function where($condition = '', $data = array())
    {
        if (!empty($condition)) {
            $this->node->condition = $condition;
            $this->node->where = $data;
        }
        return $this;
    }
    
    /**
     * Adds a JOIN instruction
     * @param string $tablename The tablename to join
     * @param string $on The join condition
     * @param string $type The join type (LEFT, INNER, RIGHT, empty)
     * @return \Arch\DB\ITable This object
     */
    public function join($tablename, $on, $type = 'LEFT')
    {
        if (!isset($this->node->join)) $this->node->join = array();
        $this->node->join[] = $this->createJoin($tablename, $on, $type);
        return $this;
    }
    
    /**
     * Joins the relations got from database driver (if any)
     * @return \Arch\DB\ITable
     */
    public abstract function joinAuto();
    
    /**
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Arch\DB\ITable This object
     */
    public function limit($limit = null, $offset = 0)
    {
        $this->node->limit = $limit;
        $this->node->offset = $offset;
        return $this;
    }
    
    /**
     * Set group by fields
     * @param string $groupby The list of fields to group
     * @return \Arch\DB\ITable This object
     */
    public function groupby($groupby)
    {
        $this->node->groupby = $groupby;
        return $this;
    }
    
    /**
     * Executes an SQL query
     * @param string $sql The SQl string. If empty, the SQL will be built
     * @param array $params The array containing user params
     * @return PDOStatement
     * @throws PDOException
     */
    public function execute($sql = '', $params = array())
    {
        try {
            if (empty($sql)) {
                // build operation syntax
                $sql = $this->nodeToString();
            }

            // now we have SQL
            $this->sql = $sql;
            unset($sql);
            
            // prepare statement
            $this->stm = $this->driver->getPDO()->prepare($this->sql);
            
            // Get PDO params
            if (!empty($this->node->set)) {
                $params = array_merge ($params, array_values($this->node->set));
            } elseif (!empty($this->node->values)) {
                $params = array_merge ($params, $this->node->values);
            }
            if (!empty($this->node->where)) {
                $params = array_merge ($params, $this->node->where);
            }
            $this->driver->log('DB query params count: '.count($params));
            
            // build PDO params
            if (!empty($params)) {
                $this->driver->dbBindParams($this->stm, $params);
            }
            
            // finally execute query
            $this->stm->execute();
            
            // log the valid query
            $this->driver->log('DB query is valid: '.$this->stm->queryString);

            // return PDOStatement for further operations
            return $this->stm;
            
        } catch (\PDOException $e) {
            
            // fail if there is no statement
            if (empty($this->stm)) {
                $this->driver->log('Invalid database statement');
            } else {
                // Log the error information and show an error page to the user
                $this->driver->log('DB query failed: '.
                        $this->stm->queryString, 'error');
                $this->driver->log('Details: '.$e->getMessage(), 'error');
            }
            
        } catch (\Exception $e) {
            $this->driver->log('Exception: '.$e->getMessage(), 'error');
        }
        
        return false;
    }
    
    /**
     * Creates a new select node
     * @return \stdClass
     */
    protected function createSelect()
    {
        $node = new \stdClass();
        $node->_type = 'SELECT';
        $node->fields = array();
        $node->from = array();
        return $node;
    }
    
    /**
     * Creates a new insert node
     * @return \stdClass
     */
    protected function createInsert()
    {
        $node = new \stdClass();
        $node->_type = 'INSERT';
        $node->table = $this->name;
        $node->fields = array();
        $node->values = array();
        return $node;
    }
    
    /**
     * Creates a new update node
     * @return \stdClass
     */
    protected function createUpdate()
    {
        $node = new \stdClass();
        $node->_type = 'UPDATE';
        $node->table = $this->name;
        $node->set = array();
        return $node;
    }
    
    /**
     * Creates a new delete node
     * @return \stdClass
     */
    protected function createDelete()
    {
        $node = new \stdClass();
        $node->_type = 'DELETE';
        $node->from = $this->name;
        return $node;
    }
    
    /**
     * Creates a new join node
     * @param string $tablename The foreign table name
     * @param string $on The join condition
     * @param string $type The join type
     * @return \stdClass
     */
    protected function createJoin($tablename, $on, $type = 'LEFT')
    {
        $node = new \stdClass();
        $node->_type = 'JOIN';
        $node->type = $type;
        $node->sql = $tablename;
        $node->on = $on;
        return $node;
    }
    
    protected function addBackTicks($items, $skip = false)
    {
        if (!is_array($items)) {
            return $skip ? $items : "`$items`";
        }
        foreach ($items as &$field) {
            $field = "`$field`";
        }
        return (string) implode(',', $items);
    }
    
    protected function nodeToString()
    {
        $fn = 'node'.ucfirst($this->node->_type).'ToString';
        $sql = $this->$fn();
        
        if (!empty($this->node->join)) {
            foreach ($this->node->join as $item) {
                $sql .= " {$item->type} JOIN ".$item->sql.' ON '.$item->on;
            }
        }
        if (!empty($this->node->condition)) {
            $sql .= ' WHERE '.$this->node->condition;
        }
        if (!empty($this->node->groupby)) {
            $sql .= ' GROUP BY '.$this->node->groupby;
        }
        if (!empty($this->node->limit)) {
            $sql .= ' LIMIT '.$this->node->limit.' OFFSET '.$this->node->offset;
        }
        return $sql;
    }
    
    protected function nodeSelectToString()
    {
        return $this->node->_type.' '.
            $this->addBackTicks($this->node->fields, true).
            ' FROM '.
            $this->addBackTicks($this->node->from);
    }
    
    protected function nodeInsertToString()
    {
        if (count($this->node->values) == 0) {
            throw new \Exception('Invalid insert values');
        }
        return $this->node->_type.
            ' INTO '.
            $this->addBackTicks($this->node->table).
            ' ('.
            $this->addBackTicks($this->node->fields).
            ') VALUES ('.
            implode(',', array_fill(0, count($this->node->values), '?')).
            ')';
    }
    
    protected function nodeUpdateToString()
    {
        if (count($this->node->set) == 0) {
            throw new \Exception('Invalid update values');
        }
        $set = $this->node->set;
        foreach ($set as $k => &$v) {
            $v = $this->addBackTicks($k).' = ?';
        }
        unset($v);
        return $this->node->_type.' '.
            $this->addBackTicks($this->node->table).' SET '.
            implode(',', $set);
    }
    
    protected function nodeDeleteToString()
    {
        return $this->node->_type.' FROM '.
            $this->addBackTicks($this->node->table);
    }

}