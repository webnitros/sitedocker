<?php

class bxSegmentRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxSegment';
    public $languageTopics = array('bxsender');
    public $beforeRemoveEvent = 'msOnBeforeRemovSegment';
    public $afterRemoveEvent = 'msOnRemovSegment';
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

return 'bxSegmentRemoveProcessor';