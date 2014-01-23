<?php

namespace Arch\DB\SQLite;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 */
class Table extends \Arch\DB\ITable
{
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
                    throw new \Exception('Invalid insert values');
                }
                $sql .= $this->node->_type.
                    ' INTO '.
                    $this->addBackTicks($this->node->table).
                    ' ('.
                    $this->addBackTicks($this->node->fields).
                    ') VALUES ('.
                    implode(',', array_fill(0, count($this->node->values), '?')).
                    ')';
                break;
            case 'UPDATE':
                if (count($this->node->set) == 0) {
                    throw new \Exception('Invalid update values');
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
            $cn = $c['name'];
            $fk = $this->driver->getForeignKeys($this->name, $cn);
            if (!empty($fk) && isset($fk['table'])) {
                $fkt = $fk['table'];
                $fkc = $fk['to'];
                $this->join($fkt, "`$this->name`.`$cn` = `$fkt`.`$fkc`");
            }
        }
        return $this;
    }
}