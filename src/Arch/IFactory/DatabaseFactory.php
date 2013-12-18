<?php

namespace Arch\IFactory;

/**
 * Description of DatabaseFactory
 *
 * @author mafonso
 */
class DatabaseFactory extends \Arch\IFactory
{
    /**
     * MyQql type
     */
    const TYPE_MYSQL = 0;
    
    /**
     * SQLite type
     */
    const TYPE_SQLITE = 1;
    
    /**
     * PostgreSQL type
     * includes HTTP headers
     */
    const TYPE_PGSQL = 2;

    /**
     * Returns a new CLI input
     * @return \Arch\Input
     */
    protected function fabricate($type) {
        $type = (int) $type;
        switch ($type) {
            case self::TYPE_SQLITE:
                return new \Arch\DB\SQLite\Driver();
            case self::TYPE_PGSQL:
                return new \Arch\DB\PostgreSQL\Driver();
            case self::TYPE_MYSQL:
                return new \Arch\DB\MySql\Driver();    
        }
        throw new \Exception('Invalid database type');
    }
}
