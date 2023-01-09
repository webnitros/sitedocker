<?php

class bxMailingRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxMailing';
    public $languageTopics = array('bxsender');
    public $beforeRemoveEvent = 'msOnBeforeRemovMailing';
    public $afterRemoveEvent = 'msOnRemovMailing';
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

return 'bxMailingRemoveProcessor';