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