<?php

namespace Arch\DB\MySql;

/**
 * Table class
 * 
 * Allows to build SQL query requests using PDO
 */
class Table extends \Arch\DB\ITable
{
    /**
     * Joins the relations got from database driver (if any)
     * @return \Arch\DB\MySql\Table
     */
    public function joinAuto()
    {
        return $this->generateJoinAuto(
            'Field', 
            'REFERENCED_TABLE_NAME', 
            'REFERENCED_COLUMN_NAME'
        );
    }
}