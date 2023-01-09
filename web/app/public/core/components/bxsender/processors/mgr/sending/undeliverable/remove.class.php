<?php
class bxUnDeliverableRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxUnDeliverable';
    public $languageTopics = array('bxsender');

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

return 'bxUnDeliverableRemoveProcessor';