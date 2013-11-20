<?php

namespace Arch\DB\MySql;

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
                    self::addBackTicks($this->node->fields, true).
                    ' FROM '.
                    self::addBackTicks($this->node->from);
                breaK;
            case 'INSERT':
                if (count($this->node->values) == 0) {
                    throw new \PDOException('Invalid insert values');
                }
                $sql .= $this->node->_type.
                    ' INTO '.
                    self::addBackTicks($this->node->table).
                    ' ('.
                    self::addBackTicks($this->node->fields).
                    ') VALUES ('.
                    implode(',', array_fill(0, count($this->node->values), '?')).
                    ')';
                break;
            case 'UPDATE':
                if (count($this->node->set) == 0) {
                    throw new \PDOException('Invalid update values');
                }
                $set = $this->node->set;
                foreach ($set as $k => &$v) $v = "`$k` = ?";
                $sql .= $this->node->_type.' '.
                    self::addBackTicks($this->node->table).' SET '.
                    implode(',', $set);
                break;
            case 'DELETE':
                $sql .= $this->node->_type.' FROM '.
                    self::addBackTicks($this->node->table);
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
}