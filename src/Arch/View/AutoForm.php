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
     * Maps configuration type to create method
     * @var array
     */
    protected $mapTypeToFn = array(
        'breakline' => 'createBreakLine',
        'label'     => 'createLabel',
        'text'      => 'createInputText',
        'hidden'    => 'createInputHidden',
        'password'  => 'createInputPassword',
        'button'    => 'createButton',
        'submit'    => 'createButtonSubmit',
        'select'    => 'createSelect',
        'textarea'  => 'createTextArea',
        'checklist' => 'createCheckList',
        'radiolist' => 'createRadioList'
    );
    
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
                ->joinAuto()
                ->where($this->config['table'].'.id = ?', array($id))
                ->fetch(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * Returns a string representation of the form
     * @return string
     */
    public function __toString()
    {
        foreach ($this->config['items'] as $item) {
            if (isset($this->mapTypeToFn[$item['type']])) {
                $fn = $this->mapTypeToFn[$item['type']];
                $this->addContent($this->$fn($item));
            }
        }
        $this->set('action', $this->config['action']);
        return parent::__toString();
    }
    
    /**
     * Creates a new breakline view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createBreakLine($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/breakline.php'
        );
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    /**
     * Creates a new label view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createLabel($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/label.php'
        );
        $config['label'] = empty($config['label']) ? '' : $config['label'];
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    /**
     * Creates a new hidden input view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createInputHidden($config)
    {
        $view = '';
        if (!empty($config['property'])) {
            $tmpl = $this->getOptionalTemplate(
                $config,
                ARCH_PATH.'/theme/form/input/hidden.php'
            );
            
            $config['name'] = $this->getDefaultName($config);
            
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
    
    /**
     * Creates a new password input view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createInputPassword($config)
    {
        $view = '';
        if (!empty($config['property'])) {
            $tmpl = $this->getOptionalTemplate(
                $config,
                ARCH_PATH.'/theme/form/input/password.php'
            );
            
            $config['name'] = $this->getDefaultName($config);
            
            $view = new \Arch\Registry\View($tmpl, $config);
        }
        return $view;
    }
    
    /**
     * Creates a new button view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createButton($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/button.php'
        );
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
    
    /**
     * Creates a new submit button view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createButtonSubmit($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/submit.php'
        );
        $config['label'] = empty($config['label']) ? '' : $config['label'];
        $config['class'] = empty($config['class']) ? '' : $config['class'];
        $v = new \Arch\Registry\View($tmpl, $config);
        return $v;
    }
    
    /**
     * Creates a new text input view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createInputText($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/input/text.php'
        );
        
        $config['name'] = $this->getDefaultName($config);
        
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
    
    /**
     * Creates a new textarea view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createTextArea($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/textarea.php'
        );
        
        $config['name'] = $this->getDefaultName($config);
        
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
    
    /**
     * Creates a new select box view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createSelect($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/select.php'
        );
        
        $config['name'] = $this->getDefaultName($config);
        $config['items'] = $this->getListItems($config);
        
        if (!isset($config['selected'])) {
            $config['selected'] = array();
            if ($this->record && !empty($config['items_table'])) {
                $fk = $this->driver->getRelationColumn(
                    (string) $this->config['table'],
                    (string) $config['items_table']
                );
                if (isset($this->record[$fk])) {
                    $config['selected'][] = $this->record[$fk];
                }
            }
        }
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    /**
     * Creates a new check list view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createCheckList($config)
    {
        $tmpl = $this->getOptionalTemplate(
            $config,
            ARCH_PATH.'/theme/form/checklist.php'
        );
        
        $config['name'] = $this->getDefaultName($config);
        $config['items'] = $this->getListItems($config);
        
        if (!isset($config['selected'])) {
            $config['selected'] = array();
            if (
                !empty($config['selected_items_table']) 
                && !empty($config['items_table']) 
                && $this->record
            ) {
                $config['selected'] = $this->getNMSelectedItems(
                    (string) $config['selected_items_table'],
                    (string) $config['items_table'],
                    (string) $this->config['table'],
                    (int) $this->record['id']
                );
            }
        }
        $config['class'] = empty($config['class']) ? 
                'checkbox' : $config['class'];
        return new \Arch\Registry\View($tmpl, $config);
    }
    
    /**
     * Creates a new radio list view
     * @param array $config A list of key/value pairs configuration params
     * @return \Arch\Registry\View
     */
    protected function createRadioList($config)
    {
        if (empty($config['tmpl'])) {
            $config['tmpl'] = ARCH_PATH.'/theme/form/radiolist.php';
        }
        $config['class'] = empty($config['class']) ? 
                'radio' : $config['class'];
        return $this->createCheckList($config);
    }
    
    /**
     * Returns a list of items that are the intersection set of two tables
     * @param string $table1
     * @param string $table2
     * @param string $table3
     * @param int $id
     * @return array
     */
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
    
    /**
     * Returns the template
     * @param array $config A list of key/value pairs configuration params
     * @param string $default The default template
     * @return string
     */
    protected function getOptionalTemplate($config, $default)
    {
        $tmpl = $default;
        if (!empty($config['tmpl']) && file_exists($config['tmpl'])) {
            $tmpl = $config['tmpl'];
        }
        return (string) $tmpl;
    }
    
    /**
     * Returns the default form field name
     * @param array $config A list of key/value pairs configuration params
     * @return string
     */
    protected function getDefaultName($config)
    {
        if (empty($config['name'])) {
            $config['name'] = isset($config['property']) ? 
                    $config['property'] : '';
        }
        return $config['name'];
    }
    
    /**
     * Returns the list of items to be selectable
     * @param array $config A list of key/value pairs configuration params
     * @param array $default The default list of items
     * @return array
     */
    protected function getListItems($config, $default = array())
    {
        if (!isset($config['items'])) {
            $config['items'] = $default;
            if (!empty($config['items_table'])) {
                $table = $this->driver->createTable(
                    (string) $config['items_table']
                );
                $config['items'] = $table->select()->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        return $config['items'];
    }
    
}
