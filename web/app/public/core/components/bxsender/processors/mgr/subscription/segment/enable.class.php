<?php

require_once(dirname(__FILE__) . '/update.class.php');

class bxSegmentEnableProcessor extends bxSegmentUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'active' => true,
        );
        return true;
    }
}

return 'bxSegmentEnableProcessor';