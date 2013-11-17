<?php
namespace Arch\View\AutoPanel;

/**
 * Description of Automatic Table
 *
 * @author mafonso
 */
class AutoTable extends \Arch\View\AutoPanel
{   
    /**
     * Returns a new panel to be rendered
     * @param array $config The panel configuration
     * @param \Arch\Driver $driver The database driver
     */
    public function __construct($config, $driver, $tmpl = '')
    {
        if (empty($tmpl)) {
            $tmpl = __DIR__.'/../../../../theme/architect/table/table.php';
        }
        parent::__construct($tmpl, $config, $driver);
        
        if (!isset($this->config['columns'])) {
            throw new \Exception('DBPanel configuration: attribute columns is required');
        }
        $this->set('columns', $config['columns']);
        $table = $driver->createTable($this->config['table']);
        $records = $table->select($this->config['select'])
                ->joinAuto()->fetchAll(\PDO::FETCH_ASSOC);
        $this->set('records', $records);
    }
    
    /**
     * Returns a new action button
     * @param array $config The button configuration
     * @param array $record The record to be used
     * @return 
     */
    public function createActionButton($config, $record)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/table/rowaction.php';
        }
        $v = new \Arch\View($tmpl, $config);
        $v->set('record', $record);
        return $v;
    }
    
    /**
     * Returns a new table cell with record property value
     * @param string $config The configuration
     * @param array $record The record
     * @return \Arch\View
     */
    public function createCellValue($config, $record)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/table/cell.php';
        }
        $v = new \Arch\View($tmpl, $config);
        $v->set('record', $record);
        return $v;
    }
}
