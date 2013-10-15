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
    protected $select;
    protected $insert;
    protected $update;
    protected $delete;
    protected $fields  = array();
    protected $from    = array();
    protected $join    = array();
    protected $set     = array();
    protected $values  = array();
    protected $where;
    protected $limit;
    
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
     * Select fields and executes a select operation
     * @param string|array $fields The string or array of fields to be selected
     * @return \PDOStatement The PDOStatement after execute
     */
    public function select($fields = '*')
    {
        $this->select = true;
        $this->fields = $fields;
        $this->from = $this->name;
        return $this->execute();
    }
    
    /**
     * Set insert values and executes an insert operation
     * @param array $values An associative array containing fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function insert($values = array())
    {
        $this->insert = true;
        $this->fields = array_keys($values);
        $this->values = array_values($values);
        return $this->execute();
    }
    
    /**
     * Set update values (associative array) and executes an update operation
     * @param array $values An associative array containing fields and values
     * @return \PDOStatement The PDOStatement after execute
     */
    public function update($values = array())
    {
        $this->update = true;
        $this->set = $values;
        return $this->execute();
    }
    
    /**
     * Executes a delete operation with where condition
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \PDOStatement The PDOStatement after execute
     */
    public function delete($condition, $data = array())
    {
        $this->delete = 1;
        $this->from = $this->name;
        $this->where($condition, $data);
        return $this->execute();
    }
    
    /**
     * Set string condition and array params
     * @param string $condition The string of conditions with placeholders (?)
     * @param array $data The values to be used as params on placeholders
     * @return \Table This object
     */
    public function where($condition, $data = array())
    {
        $this->where = array($condition, $data);
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
        $this->limit = !empty($limit) ? array($limit, $offset) : null;
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
            if ($this->select) $sql = 'SELECT';
            elseif ($this->insert) $sql = "INSERT INTO `$this->name`";
            elseif ($this->update) $sql = "UPDATE `$this->name`";
            elseif ($this->delete) $sql = 'DELETE';

            // build fields synstax
            if (!empty($this->fields)) {
                if (is_array($this->fields)) {
                    foreach ($this->fields as &$field) $field = "`$field`";
                    $fields = implode(', ', $this->fields);
                } else {
                    $fields = $this->fields;
                }
                if ($this->insert) {
                    $sql .= ' ('.$fields.') ';
                } else {
                    $sql .= " $fields ";
                }
            }

            // build from syntax
            if (!empty($this->from)) {
                $sql .= " FROM `$this->from`";
            }

            // build set and values syntax
            if (!empty($this->set) || !empty($this->values)) {
                $items = array();
                if ($this->set) {
                    $sql .= " SET ";
                    foreach ($this->set as $k => $v) {
                        $items[] = "`$k` = ?";
                    }
                    $sql .= implode(', ', $items);
                }
                if ($this->values) {
                    $sql .= " VALUES ";
                    foreach ($this->values as $k => $v) {
                        $items[] = '?';
                    }
                    $sql .= '('.implode(', ', $items).')';
                }
            }

            // build where syntax
            if (!empty($this->where)) {
                $sql .= " WHERE ".$this->where[0];
            }

            // build limit syntax
            $sql .= !empty($this->limit) ? ' LIMIT '.$this->limit[0] : '';
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
                if (!empty($this->set)) {
                    $params = array_merge ($params, array_values($this->set));
                } elseif (!empty($this->values))
                    $params = array_merge ($params, $this->values);
                if (!empty($this->where[1])) {
                    $params = array_merge ($params, $this->where[1]);
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
    private function dbBindParams($params = array())
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
}