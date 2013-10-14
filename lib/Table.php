<?php

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
    
    public function __construct($name, PDO $db = null)
    {
        $this->name = $name;
        if (empty($db)) $this->db = app()->db;
    }
    
    public function select($fields = '*') {
        $this->_select = 1;
        $this->_fields = $fields;
        $this->_from = $this->name;
        return $this->execute();
    }
    
    public function where($condition, $data = array()) {
        $this->_where = array($condition, $data);
        return $this;
    }
    
    public function limit($limit = null, $offset = 0) {
        $this->_limit = !empty($limit) ? array($limit, $offset) : null;
        return $this;
    }
    
    public function insert($values = array()) {
        $this->_insert = 1;
        $this->_fields = array_keys($values);
        $this->_values = array_values($values);
        return $this->execute();
    }
    
    public function update($values = array()) {
        $this->_update = 1;
        $this->_set = $values;
        return $this->execute();
    }
    
    public function delete() {
        $this->_delete = 1;
        $this->_from = $this->name;
        return $this->execute();
    }
    
    public function execute() {
        $sql = '';
        
        if ($this->_select) $sql = 'SELECT';
        elseif ($this->_insert) $sql = "INSERT INTO `$this->name`";
        elseif ($this->_update) $sql = "UPDATE `$this->name`";
        elseif ($this->_delete) $sql = 'DELETE';
        
        if (!empty($this->_fields)) {
            if (is_array($this->_fields)) {
                foreach ($this->_fields as &$field) $field = "`$field`";
                $fields = implode(', ',$this->_fields);
            }
            else $fields = $this->_fields;
            if ($this->_insert) $sql .= ' ('.$fields.') ';
            else $sql .= " $fields ";
        }
        
        if (!empty($this->_from)) $sql .= ' FROM '.$this->_from;
        
        if (!empty($this->_set)) {
            $sql .= " SET ";
            $params = array();
            foreach ($this->_set as $k => $v) $params[] = $k.' = ?';
            $sql .= implode(', ', $params);
        }
        
        if (!empty($this->_values)) {
            $sql .= " VALUES ";
            $params = array();
            foreach ($this->_values as $k => $v) $params[] = '?';
            $sql .= '('.implode(', ', $params).')';
        }
        
        if (!empty($this->_where)) {
            $sql .= " WHERE ".$this->_where[0];
        }
        
        $sql .= !empty($this->_limit) ? ' LIMIT '.$this->_limit[0] : '';
        $this->sql = $sql;
        unset($sql);
        
        try {
            if (!is_object($this->db)) throw new PDOException('No database connection');
            $this->stm = $this->db->prepare($this->sql);
            $bindStart = 1;
            if (!empty($this->_set)) {
                $this->dbBindParams(array_values($this->_set));
                $bindStart = count($this->_set) + 1;
            }
            if (!empty($this->_values)) {
                $this->dbBindParams($this->_values);
                $bindStart = count($this->_values) + 1;
            }
            if (!empty($this->_where[1])) {
                $this->dbBindParams($this->_where[1], $bindStart);
            }
            $this->stm->execute();
        }
        catch (PDOException $e) {
            app()->log('DB query failed: '.$this->stm->queryString, 'error');
            app()->log('Details: '.$e->getMessage(), 'error');
            m('Something wrong happened! Please try later.', 'alert alert-error');
            app()->redirect(u('/404'));
        }
        $log = $this->_select ? $this->stm->queryString : '';
        app()->log('DB query succeed: '.$log);
        
        return $this->stm;
    }
    
    /**
     * Runs an SQL file
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
    
    private function dbBindParams($params = array(), $start = 1) {
        $i = $start;
        foreach ($params as &$v) {
            $type = !is_numeric($v) ? PDO::PARAM_STR :
                    is_integer($v) ? PDO::PARAM_INT :
                    is_bool($v) ? PDO::PARAM_BOOL : 
                    PDO::PARAM_STR;
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