<?php

namespace Arch\Helper;

/**
 * Description of Query
 *
 * @author mafonso
 */
class Query extends \Arch\Helper
{
    protected $tablename;
    
    public function __construct(\Arch\App &$app) {
        parent::__construct($app);
    }
    
    public function setTablename($tablename)
    {
        $this->tablename = $tablename;
    }

    public function execute() {
        if (!$this->app->getDatabase()) {
            $this->app->initDatabase();
        }
        return $this->app->getDatabase()->createTable($this->tablename);
    }
}
