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
        $info = $this->driver->getTableInfo($this->name);
        foreach ($info as $c) {
            $cn = $c['Field'];
            $fk = $this->driver->getForeignKeys($this->name, $cn);
            if (!empty($fk) && isset($fk['REFERENCED_TABLE_NAME'])) {
                $fkt = $fk['REFERENCED_TABLE_NAME'];
                $fkc = $fk['REFERENCED_COLUMN_NAME'];
                $this->join($fkt, "`$this->name`.`$cn` = `$fkt`.`$fkc`");
            }
        }
        return $this;
    }
}