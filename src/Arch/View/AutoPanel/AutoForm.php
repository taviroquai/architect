<?php
namespace Arch\View\AutoPanel;

/**
 * Description of Automatic Form
 *
 * @author mafonso
 */
class AutoForm extends \Arch\View\AutoPanel
{
    protected $pdo;
    
    /**
     * The record (associative array)
     * @var array
     */
    protected $record;
    
    /**
     * Returns a new panel to be rendered
     * @param array $config The panel configuration
     * @param \Arch\Driver\MySql $driver The database driver
     */
    public function __construct($config, $driver, $tmpl = '')
    {
        if (empty($tmpl)) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/form.php';
        }
        parent::__construct($tmpl, $config, $driver);
        
        if (!empty($this->config['record_id'])) {
            $id = $this->config['record_id'];
            $table = $driver->createTable($this->config['table']);
            $this->record = $table->select($this->config['select'])
                ->joinAuto($driver)
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
        return parent::__toString();
    }
    
    protected function createBreakLine($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/breakline.php';
        }
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createLabel($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/label.php';
        }
        if (empty($config['label'])) {
            $config['label'] = '';
        }
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createInputHidden($config)
    {
        if (empty($config['property'])) {
            return '';
        }
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/input/hidden.php';
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        $v = new \Arch\View($tmpl, $config);
        $value = empty($config['value']) ? 
            $this->record[$config['property']] : $config['value'];
        $v->set('value', $value);
        return $v;
    }
    
    protected function createInputPassword($config)
    {
        if (empty($config['property'])) {
            return '';
        }
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/input/password.php';
        }
        if (empty($config['name'])) {
            $config['name'] = $config['property'];
        }
        $v = new \Arch\View($tmpl, $config);
        return $v;
    }
    
    protected function createButton($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/button.php';
        }
        if (
            !empty($config['action'])
            && !empty($config['property'])
            && !empty($this->record)
        ) {
            $config['action'] .= $this->record[$config['property']];
        }
        $v = new \Arch\View($tmpl, $config);
        return $v;
    }
    
    protected function createButtonSubmit($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/submit.php';
        }
        if (empty($config['label'])) {
            $config['label'] = '';
        }
        if (empty($config['class'])) {
            $config['class'] = '';
        }
        $v = new \Arch\View($tmpl, $config);
        return $v;
    }
    
    protected function createInputText($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/input/text.php';
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
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createTextArea($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/input/textarea.php';
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
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createSelect($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/select.php';
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
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createCheckList($config)
    {
        if (empty($config['tmpl'])) {
            $tmpl = __DIR__.'/../../../../theme/architect/form/checklist.php';
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
        if (!isset($config['class'])) {
            $config['class'] = 'checkbox';
        }
        return new \Arch\View($tmpl, $config);
    }
    
    protected function createRadioList($config)
    {
        if (empty($config['tmpl'])) {
            $config['tmpl'] = 
                __DIR__.'/../../../../theme/architect/form/radiolist.php';
        }
        if (!isset($config['class'])) {
            $config['class'] = 'radio';
        }
        return $this->renderCheckList($config);
    }
    
    protected function getNMSelectedItems($table1, $table2, $table3, $id)
    {
        $data = array();
        if ($table1 != $table2) {
            $column1 = $this->driver->getRelationColumn($table1, $table2);
            $column2 = $this->driver->getRelationColumn($table1, $table3);
            if (!$column1 || !$column2) {
                return $data;
            }
            $table = $this->driver->createTable($table1);
            $selected = $table->select($table1.'.'.$column1)
                    ->where($table1.".$column2 = ?", array($id))
                    ->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($selected as $row) {
                $data[] = $row[$column1];
            }
        }
        return $data;
    }
    
}
