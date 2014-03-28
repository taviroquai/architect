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
     * Joins the relations got from database driver (if any)
     * @return \Arch\DB\SQLite\Table
     */
    public function joinAuto()
    {
        return $this->generateJoinAuto(
            'name', 
            'table', 
            'to'
        );
    }
}