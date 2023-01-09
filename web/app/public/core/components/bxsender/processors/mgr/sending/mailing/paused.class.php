<?php
require_once(dirname(__FILE__) . '/update.class.php');

class bxMailingPausedProcessor extends bxMailingUpdateProcessor
{
    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->object->setStatus('paused');
        return true;
    }
    /**
     * Abstract the saving of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     * @return boolean
     */
    public function saveObject()
    {
        return true;
    }
}

return 'bxMailingPausedProcessor';
