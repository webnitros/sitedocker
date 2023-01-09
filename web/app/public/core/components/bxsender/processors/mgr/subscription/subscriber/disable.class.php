<?php

require_once(dirname(__FILE__) . '/update.class.php');

class bxSubscriberDisableProcessor extends bxSubscriberUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->properties = array(
            'state' => 'unsubscribed',
            'active' => false,
        );
        return true;
    }
}

return 'bxSubscriberDisableProcessor';
