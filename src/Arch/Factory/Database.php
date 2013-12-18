<?php

namespace Arch\Factory;

/**
 * Database factory
 * 
 * Use this to create a new database driver
 *
 * @author mafonso
 */
class Database extends \Arch\IFactory
{
    /**
     * Returns a new database driver
     * 
     * @return \Arch\DB\IDriver
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case \Arch::TYPE_DATABASE_SQLITE:
                return new \Arch\DB\SQLite\Driver();
            case \Arch::TYPE_DATABASE_PGSQL:
                return new \Arch\DB\PostgreSQL\Driver();
            case \Arch::TYPE_DATABASE_MYSQL:
                return new \Arch\DB\MySql\Driver();    
        }
        throw new \Exception('Invalid database type. Use one of \Arch::TYPE_DATABASE');
    }
}
