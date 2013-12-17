<?php

namespace Arch\Helper;

/**
 * Description of Query
 *
 * @author mafonso
 */
class Query extends \Arch\IHelper
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
            throw new \Exception('The application database was not set');
        }
        return $this->app->getDatabase()->createTable($this->tablename);
    }
}
