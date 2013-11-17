<?php
namespace Arch\View;

/**
 * Description of Automatic Panel
 *
 * @author mafonso
 */
class AutoPanel extends \Arch\View
{
    /**
     * The configuration
     * @var array
     */
    protected $config;
    
    /**
     * The database driver
     * @var \Arch\Driver
     */
    protected $driver;
    
    /**
     * Returns a new panel to be rendered
     * @param array $config The panel configuration
     * @param MySql $db The database driver
     */
    public function __construct($tmpl, $config, $driver)
    {
        parent::__construct($tmpl, $config);
        
        $this->config = $config;
        $this->driver = $driver;
        
        if (!isset($this->config['table'])) {
            throw new \Exception('DBPanel configuration: table is required');
        }
        if (!isset($this->config['select'])) {
            throw new \Exception('DBPanel configuration: select is required');
        }
    }
}
