<?php

namespace Arch;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 * TODO: separate in varous drivers (MySql, PostgreSQL, SQLite)
 */
class Table
{
    /**
     * Holds the ralational table name
     * @var string
     */
    protected $name;
    
    /**
     * Holds the PDO instance
     * @var \PDO
     */
    protected $db;
    
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
     * Holds the error logger
     * @var \Arch\Logger
     */
    protected $logger;
    
    /**
     * Returns a new Table to start querying
     * All these queries are simple and only affect 1 table based on the name
     * For more complex queries, use PDO directly
     * 
     * @param string $name The tablename
     * @param PDO $db The PDO database handler to query
     * @param \Arch\Logger $logger The logs handler
     */
    public function __construct($name, \PDO $db, \Arch\Logger $logger)
    {
        if  (!is_string($name) || empty($name)) {
            throw new \Exception('Invalid table name');
        }
        $this->name = $name;
        $this->db = $db;
        $this->logger = $logger;
        $this->node = $this->createSelect();
        
    }
    
    /**
     * Select alias
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \PDOStatement The PDOStatement after execute
     */
    public function s($fields = '*')
    {
        return $this->select($fields);
    }
    
    /**
     * Insert alias
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function i($values = array())
    {
        return $this->insert($values);
    }
    
    /**
     * Update alias
     * Set update values and executes an update operation
     * @param array $values An associative array containing fields and values
     * @return \PDOStatement The PDOStatement after execute
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
     * @return \PDOStatement The PDOStatement after execute
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
     * @return \Table This object
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
     * @return \Arch\Table Thisobject
     */
    public function j($tablename, $on, $type = 'LEFT') {
        return $this->join($tablename, $on, $type);
    }
    
    /**
     * Limit alias
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Table
     */
    public function l($limit = null, $offset = 0)
    {
        return $this->limit($limit, $offset);
    }
    
    /**
     * Set group by fields
     * @param string $groupby The list of fields to group
     * @return \Arch\Table This object
     */
    public function g($groupby)
    {
        return $this->groupby($groupby);
    }

        /**
     * Execute alias
     * @param string $sql The sql to execute (optional), build if empty
     * @param array $params The SQL params
     * @param string $redirect Redirect on error
     * @return \PDOStatement
     */
    public function run($sql = '', $params = null, $redirect = '/404')
    {
        return $this->execute($sql, $params, $redirect);
    }
    
    /**
     * Runs the query and returns all records
     * @param int $type The type of rows (array or class)
     * @return array
     */
    public function fetchAll($type = \PDO::FETCH_CLASS)
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
            return $this->db->lastInsertId($name);
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
     * @return \Table This object
     */
    public function select($fields = '*')
    {
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
     * @return \Table This object
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
     * @return \Table This object
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
     * @return \Table This object
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
     * @return \Arch\Table Thisobject
     */
    public function join($tablename, $on, $type = 'LEFT')
    {
        if (!isset($this->node->join)) $this->node->join = array();
        $this->node->join[] = $this->createJoin($tablename, $on, $type);
        return $this;
    }
    
    /**
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Table This object
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
     * @return \Arch\Table This object
     */
    public function groupby($groupby)
    {
        $this->node->groupby = $groupby;
        return $this;
    }
    
    /**
     * Executes an SQL query
     * @param string $sql The SQl string. If empty, the SQL will be built
     * @param array $params The array containing the PDO::PARAM
     * @return PDOStatement
     * @throws PDOException
     */
    public function execute($sql = '', $params = null)
    {
        try {
            if (empty($sql)) {
                // build operation syntax
                $sql = self::nodeToString($this->node);
            }

            // now we have SQL
            $this->sql = $sql;
            unset($sql);

            // provoke failures
            $this->db->setAttribute(
                \PDO::ATTR_ERRMODE, 
                \PDO::ERRMODE_EXCEPTION
            );
            
            // prepare statement
            $this->stm = $this->db->prepare($this->sql);
            
            // Get PDO params
            if ($params === null) {    
                $params = array();
                if (!empty($this->node->set)) {
                    $params = array_merge ($params, array_values($this->node->set));
                } elseif (!empty($this->node->values))
                    $params = array_merge ($params, $this->node->values);
                if (!empty($this->node->where)) {
                    $params = array_merge ($params, $this->node->where);
                }
            }
            $this->logger->log('DB query params count: '.count($params));
            
            // build PDO params
            if (is_array($params) && !empty($params)) {
                $this->dbBindParams($params);
            }
            
            // fail if there is no statement
            if ($this->stm === false) {
                throw new \PDOException('Invalid database statement');
            }
            
            // finally execute query
            $this->stm->execute();
        } catch (\PDOException $e) {
            
            // fail if there is no statement
            if (empty($this->stm)) {
                $this->logger->log('Invalid database statement');
            } else {
                // Log the error information and show an error page to the user
                $this->logger->log('DB query failed: '.
                        $this->stm->queryString, 'error');
                $this->logger->log('Details: '.$e->getMessage(), 'error');
            }
            return false;
        }
        
        // log the valid query
        $this->logger->log('DB query is valid: '.$this->stm->queryString);
        
        // return PDOStatement for further operations
        return $this->stm;
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
            
            $this->db->setAttribute(
                \PDO::ATTR_ERRMODE, 
                \PDO::ERRMODE_EXCEPTION
            );
            $this->db->beginTransaction();
            $this->db->exec($sql);
            $this->db->commit();
            return true;
            
        } catch (\PDOException $e) {
            $this->db->rollBack();
            $this->logger->log('PDO Exception: '.$e->getMessage(), 'error');
        } catch (\Exception $e) {
            $this->logger->log('Exception: '.$e->getMessage(), 'error');
        }
        return false;
    }
    
    /**
     * Bind PDO params filtered by type
     * @param array $params
     */
    protected function dbBindParams($params = array())
    {
        $i = 1;
        foreach ($params as &$v) {
            
            // set default PARAM filter
            $type = \PDO::PARAM_STR;
            
            // try to find a match
            if (is_numeric($v)) {
                if (is_integer($v)) $type = \PDO::PARAM_INT;
            } elseif (is_bool($v)) $type = \PDO::PARAM_BOOL;
            
            // bind param
            try {
                $this->stm->bindParam($i, $v, $type);
            } catch (\PDOException $e) {
                $this->logger->log("DB bind param $i failed", 'error');
            }
            $i++;
        }
    }
    
    protected function createSelect()
    {
        $node = new \stdClass();
        $node->_type = 'SELECT';
        $node->fields = array();
        $node->from = array();
        return $node;
    }
    
    protected function createInsert()
    {
        $node = new \stdClass();
        $node->_type = 'INSERT';
        $node->table = $this->name;
        $node->fields = array();
        $node->values = array();
        return $node;
    }
    
    protected function createUpdate()
    {
        $node = new \stdClass();
        $node->_type = 'UPDATE';
        $node->table = $this->name;
        $node->set = array();
        return $node;
    }
    
    protected function createDelete()
    {
        $node = new \stdClass();
        $node->_type = 'DELETE';
        $node->from = $this->name;
        return $node;
    }
    
    protected function createJoin($tablename, $on, $type = 'LEFT')
    {
        $node = new \stdClass();
        $node->_type = 'JOIN';
        $node->type = $type;
        $node->sql = $tablename;
        $node->on = $on;
        return $node;
    }
    
    protected static function nodeToString($node)
    {
        $sql = '';
        switch ($node->_type) {
            case 'SELECT':
                $sql .= $node->_type.' '.
                    self::addBackTicks($node->fields, true).
                    ' FROM '.
                    self::addBackTicks($node->from);
                breaK;
            case 'INSERT':
                if (count($node->values) == 0) {
                    throw new \PDOException('Invalid insert values');
                }
                $sql .= $node->_type.
                    ' INTO '.
                    self::addBackTicks($node->table).
                    ' ('.
                    implode(',', self::addBackTicks($node->fields)).
                    ') VALUES ('.
                    implode(',', array_fill(0, count($node->values), '?')).
                    ')';
                break;
            case 'UPDATE':
                if (count($node->set) == 0) {
                    throw new \PDOException('Invalid update values');
                }
                $set = $node->set;
                foreach ($set as $k => &$v) $v = "`$k` = ?";
                $sql .= $node->_type.' '.
                    self::addBackTicks($node->table).' SET '.
                    implode(',', $set);
                break;
            case 'DELETE':
                $sql .= $node->_type.' FROM '.
                    self::addBackTicks($node->table);
                break;
        }
        if (!empty($node->join)) {
            foreach ($node->join as $item) {
                $sql .= " {$item->type} JOIN ".$item->sql.' ON '.$item->on;
            }
        }
        if (!empty($node->condition)) {
            $sql .= ' WHERE '.$node->condition;
        }
        if (!empty($node->groupby)) {
            $sql .= ' GROUP BY '.$node->groupby;
        }
        if (!empty($node->limit)) {
            $sql .= ' LIMIT '.$node->limit.', '.$node->offset;
        }
        return $sql;
    }
    
    protected static function addBackTicks($items, $skip = false)
    {
        if (!is_array($items)) return $skip ? $items : "`$items`";
        foreach ($items as &$field) {
            $field = "`$field`";
        }
        return $items;
    }
}