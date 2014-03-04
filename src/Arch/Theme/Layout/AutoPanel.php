<?php
namespace Arch\Theme\Layout;

/**
 * Description of Automatic Panel
 *
 * @author mafonso
 */
abstract class AutoPanel extends \Arch\Theme\Layout
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
     * Configures the AutoPanel
     * @param array $config
     * @param \Arch\DB\IDriver $database
     */
    public function configure($config, \Arch\DB\IDriver $database) {
        $this->setConfig($config);
        $this->setDatabaseDriver($database);
    }
    
    /**
     * The panel configuration - associative array
     * @param array $config
     */
    protected function setConfig($config)
    {
        $this->config = $config;
        if (empty($this->config['table'])) {
            throw new \Exception('AutoPanel configuration: table is required');
        }
        if (empty($this->config['select'])) {
            throw new \Exception('AutoPanel configuration: select is required');
        }
    }
    
    /**
     * Sets the required database driver
     * @param \Arch\DB\IDriver $database
     */
    protected function setDatabaseDriver(\Arch\DB\IDriver $database)
    {
        $this->driver = $database;
    }
}
