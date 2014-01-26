<?php

namespace Arch\Helper;

/**
 * Description of Query
 *
 * @author mafonso
 */
class Query extends \Arch\IHelper
{
    /**
     * Holds the table name to be queried
     * @var string
     */
    protected $tablename;
    
    /**
     * Returns a new Query helper
     * @param \Arch\App $app
     */
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    /**
     * Sets the table name
     * @param string $tablename
     */
    public function setTablename($tablename)
    {
        $this->tablename = (string) $tablename;
    }

    /**
     * Returns a new table to start quering
     * @return \Arch\DB\ITable
     * @throws \Exception
     */
    public function run() {
        if (!$this->app->getDatabase()) {
            throw new \Exception('The application database was not set');
        }
        return $this->app->getDatabase()->createTable($this->tablename);
    }
}
