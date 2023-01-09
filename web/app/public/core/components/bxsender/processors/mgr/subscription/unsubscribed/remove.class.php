<?php
class bxUnSubscribedRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'bxUnSubscribed';
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

return 'bxUnSubscribedRemoveProcessor';