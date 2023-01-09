<?php

/**
 * Create an Subscriber
 */
class bxUnDeliverableCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'bxUnDeliverable';
    public $classKey = 'bxUnDeliverable';
    public $languageTopics = array('bxsender');
    public $permission = '';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        return !$this->hasErrors();
    }
}

return 'bxUnDeliverableCreateProcessor';
