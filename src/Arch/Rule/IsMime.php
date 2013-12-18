<?php

namespace Arch\Rule;

/**
 * IsMime rule class
 */
class IsMime extends \Arch\IRule
{
    /**
     * Execute isMime
     * @return \Arch\Rule\IsMime
     */
    public function run()
    {
        $v = $this->params[0];
        $list = $this->params[1];
        if (!is_array($list)) {
            $list = array($list);
        }
        $finfo = new \finfo(FILEINFO_MIME);
        $type = strpos($finfo->file($v), ';') ? 
                explode(';', $finfo->file($v)) :
                $finfo->file($v);
        $type = reset($type);
        $this->result = in_array($type, $list);
        return $this;
    }
}