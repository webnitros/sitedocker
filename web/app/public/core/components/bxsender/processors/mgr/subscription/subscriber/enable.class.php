<?php
require_once(dirname(__FILE__) . '/update.class.php');
class bxSubscriberEnableProcessor extends bxSubscriberUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'state' => 'subscribe',
            'active' => true,
        );
        return true;
    }
}

return 'bxSubscriberEnableProcessor';
