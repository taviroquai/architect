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
    
    /**
     * Create database from config
     */
    public function createFromConfig(
        \Arch\Registry\Config $config,
        \Arch\Logger $logger
    ) {
        $factory = new \Arch\IFactory\DatabaseFactory();
        $type = $config->get('DB_DRIVER');
        $database = $config->get('DB_DATABASE');
        $host = $config->get('DB_HOST');
        $user = $config->get('DB_USER');
        $pass = $config->get('DB_PASS');
        
        switch ($type) {
            case 'sqlite':
                $db = $factory->fabricate(self::TYPE_SQLITE);
                $db->setLogger($logger);
                $db->connect($host, $database, $user, $pass);
                return $db;
            case 'pgsql':
                $db = $factory->fabricate(self::TYPE_PGSQL);
                $db->setLogger($logger);
                $db->connect($host, $database, $user, $pass);
                return $db;
            case 'mysql':
                $db = $factory->fabricate(self::TYPE_MYSQL);
                $db->setLogger($logger);
                $db->connect($host, $database, $user, $pass);
                return $db;
        }
    }
}
