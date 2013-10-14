<?php

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 * TODO: separate in varous drivers (MySql, PostgreSQL, SQLite)
 */
class Table {
    
    protected $name;
    protected $db;
    protected $stm;
    protected $sql;
    protected $_select;
    protected $_insert;
    protected $_update;
    protected $_delete;
    protected $_fields  = array();
    protected $_from    = array();
    protected $_join    = array();
    protected $_set     = array();
    protected $_values  = array();
    protected $_where;
    protected $_limit;
    
    /**
     * Returns a new Table to start querying
     * All these queries are simple and only affect 1 table based on the name
     * For more complex queries, use PDO directly
     * 
     * @param string $name The tablename
     * @param PDO $db The PDO database handler to query
     */
    public function __construct($name, PDO $db = null)
    {
        $this->name = $name;
        if (empty($db)) $this->db = app()->db;
    }
    
    /**
     * Select alias
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \PDOStatement The PDOStatement after execute
     */
    public function s($fields = '*') {
        return $this->select($fields);
    }
    
    /**
     * Insert alias
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing the fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function i($values = array()) {
        return $this->insert($values);
    }
    
    /**
     * Update alias
     * Set update values and executes an update operation
     * @param array $values An associative array containing the fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function u($values = array()) {
        return $this->update($values);
    }
    
    /**
     * Delete alias
     * Executes a delete operation with where condition
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \PDOStatement The PDOStatement after execute
     */
    public function d($condition, $data = array()) {
        return $this->delete($condition, $data);
    }
    
    /**
     * Where alias
     * Set string condition and array params
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Table This object
     */
    public function w($condition, $data = array()) {
        return $this->where($condition, $data);
    }
    
    /**
     * Limit alias
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Table
     */
    public function l($limit = null, $offset = 0) {
        return $this->limit($limit, $offset);
    }
    
    /**
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \PDOStatement The PDOStatement after execute
     */
    public function select($fields = '*') {
        $this->_select = 1;
        $this->_fields = $fields;
        $this->_from = $this->name;
        return $this->execute();
    }
    
    /**
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing the fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function insert($values = array()) {
        $this->_insert = 1;
        $this->_fields = array_keys($values);
        $this->_values = array_values($values);
        return $this->execute();
    }
    
    /**
     * Set update values (associative array) and executes an update operation
     * @param array $values An associative array containing the fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function update($values = array()) {
        $this->_update = 1;
        $this->_set = $values;
        return $this->execute();
    }
    
    /**
     * Executes a delete operation with where condition
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \PDOStatement The PDOStatement after execute
     */
    public function delete($condition, $data = array()) {
        $this->_delete = 1;
        $this->_from = $this->name;
        $this->where($condition, $data);
        return $this->execute();
    }
    
    /**
     * Set string condition and array params
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Table This object
     */
    public function where($condition, $data = array()) {
        $this->_where = array($condition, $data);
        return $this;
    }
    
    /**
     * Set limit and offset
     * @param integer $limit The limit, an integer
     * @param integer $offset The offset, an integer
     * @return \Table This object
     */
    public function limit($limit = null, $offset = 0) {
        $this->_limit = !empty($limit) ? array($limit, $offset) : null;
        return $this;
    }
    
    /**
     * Executes an SQL query
     * @param string $sql The SQl string. If empty, the SQL will be built
     * @param array $params The array containing the PDO::PARAM
     * @return PDOStatement
     * @throws PDOException
     */
    public function execute($sql = '', $params = null) {
        
        if (empty($sql)) {
            // build SQL from this properies
            
            // build operation syntax
            if ($this->_select) $sql = 'SELECT';
            elseif ($this->_insert) $sql = "INSERT INTO `$this->name`";
            elseif ($this->_update) $sql = "UPDATE `$this->name`";
            elseif ($this->_delete) $sql = 'DELETE';

            // build fields synstax
            if (!empty($this->_fields)) {
                if (is_array($this->_fields)) {
                    foreach ($this->_fields as &$field) $field = "`$field`";
                    $fields = implode(', ',$this->_fields);
                }
                else $fields = $this->_fields;
                if ($this->_insert) $sql .= ' ('.$fields.') ';
                else $sql .= " $fields ";
            }

            // build from syntax
            if (!empty($this->_from)) $sql .= " FROM `$this->_from`";

            // build set and values syntax
            if (!empty($this->_set)|| !empty($this->_values)) {
                $items = array();
                if ($this->_set) {
                    $sql .= " SET ";
                    foreach ($this->_set as $k => $v) $items[] = "`$k` = ?";
                    $sql .= implode(', ', $items);
                }
                if ($this->_values) {
                    $sql .= " VALUES ";
                    foreach ($this->_values as $k => $v) $items[] = '?';
                    $sql .= '('.implode(', ', $items).')';
                }
            }

            // build where syntax
            if (!empty($this->_where)) $sql .= " WHERE ".$this->_where[0];

            // build limit syntax
            $sql .= !empty($this->_limit) ? ' LIMIT '.$this->_limit[0] : '';
        }
        
        // now we have SQL
        $this->sql = $sql;
        unset($sql);
        
        try {
            
            // fail if there is not database connection
            if (!is_object($this->db)) throw new PDOException('No database connection');
            
            // prepare statement
            $this->stm = $this->db->prepare($this->sql);
            
            // Get PDO params
            if ($params === null) {    
                $params = array();
                if (!empty($this->_set)) 
                    $params = array_merge ($params, array_values($this->_set));
                elseif (!empty($this->_values))
                    $params = array_merge ($params, $this->_values);
                if (!empty($this->_where[1]))
                    $params = array_merge ($params, $this->_where[1]);
            }
            app()->log('DB query params count: '.count($params));
            
            // build PDO params
            if (is_array($params) && !empty($params))
                $this->dbBindParams($params);
            
            // finally execute query
            $this->stm->execute();
        }
        catch (PDOException $e) {
            
            // Log the error information and show an error page to the user
            app()->log('DB query failed: '.$this->stm->queryString, 'error');
            app()->log('Details: '.$e->getMessage(), 'error');
            m('Something wrong happened! Please try later.', 'alert alert-error');
            app()->redirect(u('/404'));
        }
        
        // log the valid query
        app()->log('DB query is valid: '.$this->stm->queryString);
        
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
    public function install($moduleName) {
        try {
            $engine = reset(explode(':', DBDSN));
            $filename = BASEPATH.'/module/enable/'.$moduleName.'/db/'.$engine.'.sql';
            if (!file_exists($filename)) 
                throw new Exception('SQL file not found: '.$filename);
            $sql = file_get_contents($filename);
            if (!is_object($this->db)) 
                throw new Exception('No database connection');
            if ($this->db->exec($sql) === false) throw new Exception();
        } catch (PDOException $e) {
            app()->log($e->getMessage(), 'error');
            m('Could not install module '.$moduleName, 'alert alert-error');
            app()->redirect(u('/404'));
        }
        catch (Exception $e) {
            app()->log($e->getMessage(), 'error');
            m('Could not install module '.$moduleName, 'alert alert-error');
            app()->redirect(u('/404'));
        }
    }
    
    /**
     * Bind PDO params filtered by type
     * @param array $params
     */
    private function dbBindParams($params = array()) {
        $i = 1;
        foreach ($params as &$v) {
            
            // set default PARAM filter
            $type = PDO::PARAM_STR;
            
            // try to find a match
            if (is_numeric($v)) {
                if (is_integer($v)) $type = PDO::PARAM_INT;
            }
            if (is_bool($v)) $type = PDO::PARAM_BOOL;
            
            // bind param
            try {
                $this->stm->bindParam($i, $v, $type);
            }
            catch (PDOException $e) {
                app()->log("DB bind param $i failed", 'error');
            }
            $i++;
        }
    }
}