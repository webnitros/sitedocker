<?php
class bxSubscriberRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxSubscriber';
    public $languageTopics = array('bxsender');
    public $beforeRemoveEvent = 'msOnBeforeRemoveSubscriber';
    public $afterRemoveEvent = 'msOnRemoveSubscriber';
    public $permission = 'mssetting_save';

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        return parent::initialize();
    }
}

return 'bxSubscriberRemoveProcessor';