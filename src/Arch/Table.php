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
    protected $name;
    protected $db;
    protected $stm;
    protected $sql;
    protected $node;
    
    /**
     * Returns a new Table to start querying
     * All these queries are simple and only affect 1 table based on the name
     * For more complex queries, use PDO directly
     * 
     * @param string $name The tablename
     * @param PDO $db The PDO database handler to query
     */
    public function __construct($name, \PDO $db = null)
    {
        $this->name = $name;
        if (empty($db)) {
            $db = \Arch\App::Instance()->db;
        }
        $this->db = $db;
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
    public function d($condition, $data = array())
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
    public function delete($condition, $data = array())
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
    public function where($condition, $data = array())
    {
        $this->node->condition = $condition;
        $this->node->where = $data;
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
     * Executes an SQL query
     * @param string $sql The SQl string. If empty, the SQL will be built
     * @param array $params The array containing the PDO::PARAM
     * @return PDOStatement
     * @throws PDOException
     */
    public function execute($sql = '', $params = null, $redirect = '/404')
    {
        if (empty($sql)) {
            // build SQL from this properies
            
            // build operation syntax
            $sql = self::nodeToString($this->node);
        }
        
        // now we have SQL
        $this->sql = $sql;
        unset($sql);
        
        try {
            
            // fail if there is not database connection
            if (!is_object($this->db)) {
                throw new \PDOException('Invalid database connection');
            }
            
            // prepare statement
            $this->stm = $this->db->prepare($this->sql);
            
            // fail if there is no statement
            if ($this->stm === false) {
                throw new \PDOException('Invalid database statement');
            }
            
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
            \Arch\App::Instance()->log('DB query params count: '.count($params));
            
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
            
            \Arch\App::Instance()->addMessage('Something wrong happened! 
                    Please try later.', 'alert alert-error');
            
            // fail if there is no statement
            if (empty($this->stm)) {
                \Arch\App::Instance()->log('Invalid database statement');
            } else {
                // Log the error information and show an error page to the user
                \Arch\App::Instance()->log('DB query failed: '.
                        $this->stm->queryString, 'error');
                \Arch\App::Instance()->log('Details: '.$e->getMessage(), 'error');
            }
            if (empty($redirect)) {
                return false;
            }
            \Arch\App::Instance()->redirect(
                \Arch\App::Instance()->url($redirect)
            );
        }
        
        // log the valid query
        \Arch\App::Instance()->log(
                'DB query is valid: '.$this->stm->queryString);
        
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
            if (!is_object($this->db)) {
                throw new \Exception('No database connection');
            }
            $this->db->beginTransaction();
            $r = $this->db->exec($sql);
            if ( $r === false) {
                $this->db->rollBack();
                throw new \Exception();
            }
            $this->db->commit();
            return true;
            
        } catch (\PDOException $e) {
            
        } catch (\Exception $e) {
            
        }
        \Arch\App::Instance()->log($e->getMessage(), 'error');
        \Arch\App::Instance()->redirect(\Arch\App::Instance()->url('/404'));
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
                \Arch\App::Instance()->log("DB bind param $i failed", 'error');
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
        if (!empty($node->condition)) {
            $sql .= ' WHERE '.$node->condition;
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