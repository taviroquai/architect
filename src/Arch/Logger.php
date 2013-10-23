<?php

namespace Arch;

/**
 * Description of Logger
 *
 * @author mafonso
 */
class Logger
{
    
    protected $filename;
    protected $handler;
    
    public function __construct($filename = '')
    {
        $this->filename = $filename;
        
        if (is_writable($this->filename)) {
            $this->handler = @fopen($filename, 'a');
        }
    }
    
    public function log($msg, $label = 'access', $nlb = false)
    {
        if (!is_resource($this->handler)) return false;
        
        $secs = round(microtime(true)-floor(microtime(true)), 3);
        $time = date('Y-m-d H:i:s').' '.sprintf('%0.3f', $secs).'ms';
        $msg = strtoupper($label).' '.$time.' '.$msg.PHP_EOL;
        if ($nlb) {
            $msg = PHP_EOL.$msg;
        }
        if (is_resource($this->handler)) {
            fwrite($this->handler, $msg);
        }
        return true;
    }
    
    public function close() {
        if (is_resource($this->handler)) fclose ($this->handler);
    }
}

?>
