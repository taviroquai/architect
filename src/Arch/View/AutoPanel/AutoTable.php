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
     * Holds the table pagination
     * @var \Arch\View\Pagination
     */
    public $pagination;
    
    /**
     * Holds the database table
     * @var \Arch\DB\Table
     */
    protected $table;

        /**
     * Returns a new panel to be rendered
     * @param array $config The panel configuration
     * @param \Arch\Driver $driver The database driver
     */
    public function __construct($config, $driver, $pagination)
    {
        $tmpl = implode(
            DIRECTORY_SEPARATOR, 
            array(ARCH_PATH, 'theme', 'table', 'table.php')
        );
        parent::__construct($tmpl, $config, $driver);
        
        if (!isset($this->config['columns'])) {
            throw new \Exception('DBPanel configuration: attribute columns is required');
        }
        $this->set('columns', $config['columns']);
        if (empty($config['pagination'])) {
            $config['pagination'] = 10;
        }
        $this->table = $driver->createTable($this->config['table']);
        $all = $this->table->select($this->config['select'])
                ->joinAuto()
                ->fetchAll(\PDO::FETCH_ASSOC);
        $this->pagination = $pagination;
        $this->pagination->setLimit($config['pagination']);
        $this->pagination->setTotalItems(count($all));
    }
    
    /**
     * Returns a new action button
     * @param array $config The button configuration
     * @param array $record The record to be used
     * @return 
     */
    protected function createActionButton($config, $record)
    {
        if (empty($config['tmpl'])) {
            $tmpl = implode(
                DIRECTORY_SEPARATOR,
                array(ARCH_PATH, 'theme', 'table', 'rowaction.php')
            );
        }
        if (!isset($config['action'])) {
            $config['action'] = '';
        }
        if (!empty($config['property'])) {
            $config['action'] .= $record[$config['property']];
        }
        if (!isset($config['class'])) {
            $config['class'] = 'btn';
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
    protected function createCellValue($config, $record)
    {
        if (empty($config['tmpl'])) {
            $tmpl = implode(
                DIRECTORY_SEPARATOR,
                array(ARCH_PATH, 'theme', 'table', 'cell.php')
            );
        }
        $config['value'] = 'undefined';
        if (!empty($config['property'])) {
            $config['value'] = isset($record[$config['property']]) ?
                $record[$config['property']] : '';
        }
        $v = new \Arch\View($tmpl, $config);
        $v->set('record', $record);
        return $v;
    }
    
    public function __toString()
    {
        $records = $this->table->select($this->config['select'])
            ->joinAuto()
            ->limit(
                $this->pagination->getLimit(),
                $this->pagination->getOffset()
            )
            ->fetchAll(\PDO::FETCH_ASSOC);
        $rows = array();
        foreach ($records as $record) {
            $cols = array();
            foreach ($this->config['columns'] as $col) {
                switch ($col['type']) {
                    case 'action':
                        $v = $this->createActionButton($col, $record);
                        $cols[] = '<td style="width: 30px">'.$v.'</td>';
                        break;
                    default:
                        $v = $this->createCellValue($col, $record);
                        $cols[] = '<td>'.$v.'</td>';
                }
            }
            $rows[] = $cols;
        }
        $this->set('rows', $rows);
        $this->addContent($this->pagination);
        return parent::__toString();
    }
}
