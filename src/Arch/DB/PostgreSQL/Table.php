<?php

namespace Arch\DB\PostgreSQL;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 */
class Table extends \Arch\DB\Table
{
    /**
     * Returns a new MySql Table
     * @param string $name The table name
     * @param \Arch\DB\Driver $driver
     */
    public function __construct($name, \Arch\DB\Driver $driver)
    {
        parent::__construct($name, $driver);
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
            return $stm->fetchColumn(0);
        }
        return false;
    }

    /**
     * Transforms this node tree to SQL string
     * @return string
     * @throws \PDOException
     */
    protected function nodeToString()
    {
        $sql = '';
        switch ($this->node->_type) {
            case 'SELECT':
                $sql .= $this->node->_type.' '.
                    $this->addBackTicks($this->node->fields, true).
                    ' FROM '.
                    $this->addBackTicks($this->node->from);
                breaK;
            case 'INSERT':
                if (count($this->node->values) == 0) {
                    throw new \PDOException('Invalid insert values');
                }
                $sql .= $this->node->_type.
                    ' INTO '.
                    $this->addBackTicks($this->node->table).
                    ' ('.
                    $this->addBackTicks($this->node->fields).
                    ') VALUES ('.
                    implode(',', array_fill(0, count($this->node->values), '?')).
                    ')';
                $sql .= ' RETURNING id';
                break;
            case 'UPDATE':
                if (count($this->node->set) == 0) {
                    throw new \PDOException('Invalid update values');
                }
                $set = $this->node->set;
                foreach ($set as $k => &$v) $v = $this->addBackTicks($k).' = ?';
                $sql .= $this->node->_type.' '.
                    $this->addBackTicks($this->node->table).' SET '.
                    implode(',', $set);
                break;
            case 'DELETE':
                $sql .= $this->node->_type.' FROM '.
                    $this->addBackTicks($this->node->table);
                break;
        }
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
    
    /**
     * Joins the relations got from database driver (if any)
     * @return \Arch\Table
     */
    public function joinAuto()
    {
        $info = $this->driver->getTableInfo($this->name);
        foreach ($info as $c) {
            $cn = $c['column_name'];
            $fk = $this->driver->getForeignKeys($this->name, $cn);
            if (!empty($fk) && isset($fk['foreign_table_name'])) {
                $fkt = $fk['foreign_table_name'];
                $fkc = $fk['foreign_column_name'];
                $this->join($fkt, "`$this->name`.`$cn` = `$fkt`.`$fkc`");
            }
        }
        return $this;
    }
    
    protected function addBackTicks($items, $skip = false)
    {
        if (!is_array($items)) return $skip ? $items : '"'.$items.'"';
        foreach ($items as &$field) {
            $field = '"'.$field.'"';
        }
        return (string) implode(',', $items);
    }
}