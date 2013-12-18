<?php
namespace Arch\View;

/**
 * Description of Automatic Form
 *
 * @author mafonso
 */
class AutoForm extends \Arch\Theme\Layout\AutoPanel
{
    /**
     * The record (associative array)
     * @var array
     */
    protected $record;
    
    /**
     * Returns a new panel to be rendered
     */
    public function __construct()
    {
        $tmpl = implode(
            DIRECTORY_SEPARATOR,
            array(ARCH_PATH, 'theme', 'form', 'form.php')
        );
        parent::__construct($tmpl);
    }
    
    /**
     * The form configuration - associative array
     * @param array $config
     */
    public function setConfig($config) {
        parent::setConfig($config);
        $this->config['action'] = empty($this->config['action']) ? '' 
                : $this->config['action'];
    }
    
    /**
     * The form database driver
     * @param \Arch\DB\IDriver $database
     */
    public function setDatabaseDriver(\Arch\DB\IDriver $database) {
        parent::setDatabaseDriver($database);
        if (empty($this->config)) {
            throw new \Exception('Missing configuration');
        }
        if (!empty($this->config['record_id'])) {
            $id = $this->config['record_id'];
            $table = $database->createTable($this->config['table']);
            $this->record = $table->select($this->config['select'])
                ->joinAuto($database)
                ->where($this->config['table'].'.id = ?', array($id))
                ->fetch(\PDO::FETCH_ASSOC);
        }
    }

    public function __toString()
    {
        foreach ($this->config['items'] as $item) {
            switch ($item['type']) {
                case 'label':
                    $this->addContent($this->createLabel($item));
                    break;
                case 'hidden':
                    $this->addContent($this->createInputHidden($item));
                    break;
                case 'password': 
                    $this->addContent($this->createInputPassword($item));
                    break;
                case 'button':
                    $this->addContent($this->createButton($item));
                    break;
                case 'submit': 
                    $this->addContent($this->createButtonSubmit($item));
                    break;
                case 'select': 
                    $this->addContent($this->createSelect($item));
                    break;
                case 'textarea':
                    $this->addContent($this->createTextArea($item));
                    break;
                case 'checklist': 
                    $this->addContent($this->createCheckList($item));
                    break;
                case 'radiolist': 
                    $this->addContent($this->createRadioList($item));
                    break;
                case 'breakline': 
                    $this->addContent($this->createBreakLine($item));
                    break;
                default:
                    $this->addContent($this->createInputText($item));
            }
        }
        $this->set('action', $this->config['action']);
        return parent::__toString();
    }
    
    protected function createBreakLine($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/breakline.php';
        }
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createLabel($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/label.php';
        }
        $config['label'] = empty($config['label']) ? '' : $config['label'];
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createInputHidden($config)
    {
        $view = '';
        if (!empty($config['property'])) {
            if (empty($config['tmpl'])) {
                $tmpl = ARCH_PATH.'/theme/form/input/hidden.php';
            }
            if (empty($config['name'])) {
                $config['name'] = $config['property'];
            }
            if (!isset($config['value'])) {
                $config['value'] = '';
                if (isset($this->record[$config['property']])) {
                    $config['value'] = $this->record[$config['property']];
                }
            }
            $view = new \Arch\Registry\View($tmpl, $config);
        }
        return $view;
    }
    
    protected function createInputPassword($config)
    {
        $view = '';
        if (!empty($config['property'])) {
            if (empty($config['tmpl'])) {
                $tmpl = ARCH_PATH.'/theme/form/input/password.php';
            }
            if (empty($config['name'])) {
                $config['name'] = $config['property'];
            }
            $view = new \Arch\Registry\View($tmpl, $config);
        }
        return $view;
    }
    
    protected function createButton($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/button.php';
        }
        if (
            !empty($config['action'])
            && !empty($config['property'])
            && !empty($this->record)
        ) {
            $config['action'] .= $this->record[$config['property']];
        }
        $v = new \Arch\Registry\View($tmpl, $config);
        return $v;
    }
    
    protected function createButtonSubmit($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/submit.php';
        }
        $config['label'] = empty($config['label']) ? '' : $config['label'];
        $config['class'] = empty($config['class']) ? '' : $config['class'];
        $v = new \Arch\Registry\View($tmpl, $config);
        return $v;
    }
    
    protected function createInputText($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/input/text.php';
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        if (!isset($config['value'])) {
            $config['value'] = '';
            if (
                isset($config['property']) 
                && isset($this->record[$config['property']])
            ) {
                $config['value'] = $this->record[$config['property']];
            }
        }
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createTextArea($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/input/textarea.php';
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        if (!isset($config['value'])) {
            $config['value'] = '';
            if (
                isset($config['property']) 
                && isset($this->record[$config['property']])
            ) {
                $config['value'] = $this->record[$config['property']];
            }
        }
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createSelect($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = ARCH_PATH.'/theme/form/select.php';
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        
        if (!isset($config['items'])) {
            $config['items'] = array();
            if (!empty($config['items_table'])) {
                $table = $this->driver->createTable($config['items_table']);
                $config['items'] = $table->select()->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        
        if (!isset($config['selected'])) {
            $config['selected'] = array();
            if ($this->record && !empty($config['items_table'])) {
                $fk = $this->driver->getRelationColumn(
                    $this->config['table'],
                    $config['items_table']
                );
                if (isset($this->record[$fk])) {
                    $config['selected'][] = $this->record[$fk];
                }
            }
        }
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createCheckList($config)
    {
        $tmpl = ARCH_PATH.'/theme/form/checklist.php';
        if (!empty($config['tmpl'])) {
            $tmpl = $config['tmpl'];
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        
        if (!isset($config['items'])) {
            $config['items'] = array();
            if (!empty($config['items_table'])) {
                $table = $this->driver->createTable($config['items_table']);
                $config['items'] = $table->select()->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        
        if (!isset($config['selected'])) {
            $config['selected'] = array();
            if (
                !empty($config['selected_items_table']) 
                && !empty($config['items_table']) 
                && $this->record
            ) {
                $config['selected'] = $this->getNMSelectedItems(
                    $config['selected_items_table'],
                    $config['items_table'],
                    $this->config['table'],
                    $this->record['id']
                );
            }
        }
        $config['class'] = empty($config['class']) ? 
                'checkbox' : $config['class'];
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    protected function createRadioList($config)
    {
        if (empty($config['tmpl'])) {
            $config['tmpl'] = 
                ARCH_PATH.'/theme/form/radiolist.php';
        }
        $config['class'] = empty($config['class']) ? 
                'radio' : $config['class'];
        return $this->createCheckList($config);
    }
    
    protected function getNMSelectedItems($table1, $table2, $table3, $id)
    {
        $data = array();
        if ($table1 != $table2) {
            $column1 = $this->driver->getRelationColumn($table1, $table2);
            $column2 = $this->driver->getRelationColumn($table1, $table3);
            if ($column1 && $column2) {
                $table = $this->driver->createTable($table1);
                $selected = $table->select($table1.'.'.$column1)
                        ->where($table1.".$column2 = ?", array($id))
                        ->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($selected as $row) {
                    $data[] = $row[$column1];
                }
            }
        }
        return $data;
    }
    
}
