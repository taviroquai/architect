<?php

namespace Arch\DB\PostgreSQL;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 */
class Table extends \Arch\DB\ITable
{
    /**
     * Returns insert string
     * @return string
     */
    protected function nodeInsertToString()
    {
        return parent::nodeInsertToString() . ' RETURNING id';
    }
    
    /**
     * Joins the relations got from database driver (if any)
     * @return \Arch\DB\PostgreSQL\Table
     */
    public function joinAuto()
    {
        return $this->generateJoinAuto(
            'column_name', 
            'foreign_table_name', 
            'foreign_column_name'
        );
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
    
    protected function addBackTicks($items, $skip = false)
    {
        if (!is_array($items)) return $skip ? $items : '"'.$items.'"';
        foreach ($items as &$field) {
            $field = '"'.$field.'"';
        }
        return (string) implode(',', $items);
    }
}