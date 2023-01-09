<?php

class bxQueueRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxQueue';
    public $languageTopics = array('bxsender');
    public $beforeRemoveEvent = 'msOnBeforeRemoveQueue';
    public $afterRemoveEvent = 'msOnRemoveQueue';
    public $permission = 'bxsender_remove';

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

return 'bxQueueRemoveProcessor';