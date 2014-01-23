<?php
namespace Arch\View;

/**
 * Description of Automatic Table
 *
 * @author mafonso
 */
class AutoTable extends \Arch\Theme\Layout\AutoPanel
{
    /**
     * Holds the table pagination
     * @var \Arch\View\Pagination
     */
    protected $pagination;
    
    /**
     * Holds the database table
     * @var \Arch\DB\Table
     */
    protected $table;

        /**
     * Returns a new panel to be rendered
     */
    public function __construct()
    {
        $tmpl = implode(
            DIRECTORY_SEPARATOR, 
            array(ARCH_PATH, 'theme', 'table', 'table.php')
        );
        parent::__construct($tmpl);
    }
    
    /**
     * The table configuration - associative array
     * @param array $config
     */
    public function setConfig($config) {
        parent::setConfig($config);
        if (!isset($this->config['columns'])) {
            throw new \Exception('DBPanel configuration: attribute columns is required');
        }
        $this->set('columns', $config['columns']);
    }
    
    /**
     * Sets the database driver
     * @param \Arch\DB\IDriver $database
     */
    public function setDatabaseDriver(\Arch\DB\IDriver $database) {
        parent::setDatabaseDriver($database);
        if (empty($this->config)) {
            throw new \Exception('Missing configuration');
        }
        $this->table = $database->createTable($this->config['table']);
    }
    
    /**
     * Sets the table pagination
     * @param \Arch\View\Pagination $pagination
     */
    public function setPagination(\Arch\View\Pagination $pagination)
    {
        if (empty($this->config)) {
            throw new \Exception('Missing configuration');
        }
        if (empty($this->table)) {
            throw new \Exception('Missing database driver');
        }
        $this->pagination = $pagination;
        $all = $this->table->select($this->config['select'])
                ->joinAuto()
                ->fetchAll(\PDO::FETCH_ASSOC);
        $this->pagination->setTotalItems(count($all));
    }
    
    /**
     * Returns the table pagination
     * @return \Arch\View\Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Returns a new action button
     * @param array $config The button configuration
     * @param array $record The record to be used
     * @return 
     */
    protected function createActionButton($config, $record)
    {
        $tmpl = implode(
            DIRECTORY_SEPARATOR,
            array(ARCH_PATH, 'theme', 'table', 'rowaction.php')
        );
        if (!empty($config['tmpl']) && file_exists($config['tmpl'])) {
            $tmpl = $config['tmpl'];
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
        $v = new \Arch\Registry\View($tmpl, $config);
        $v->set('record', $record);
        return $v;
    }
    
    /**
     * Returns a new table cell with record property value
     * @param array $config The configuration
     * @param array $record The record
     * @return \Arch\View
     */
    protected function createCellValue($config, $record)
    {
        $tmpl = implode(
            DIRECTORY_SEPARATOR,
            array(ARCH_PATH, 'theme', 'table', 'cell.php')
        );
        if (!empty($config['tmpl']) && file_exists($config['tmpl'])) {
            $tmpl = $config['tmpl'];
        }
        $config['value'] = 'undefined';
        if (!empty($config['property'])) {
            $config['value'] = isset($record[$config['property']]) ?
                $record[$config['property']] : '';
        }
        $v = new \Arch\Registry\View($tmpl, $config);
        $v->set('record', $record);
        return $v;
    }
    
    /**
     * Returns a string representing the table
     * @return string
     */
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
