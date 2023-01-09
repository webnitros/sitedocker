<?php
class crossManagerHybridauthItemRemoveProcessor extends modObjectRemoveProcessor
{
    public $objectType = 'crossManagerHybridauthItem';
    public $classKey = 'crossManagerHybridauthItem';
    public $languageTopics = ['crossmanagerhybridauth:manager'];
    #public $permission = 'remove';

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

return 'crossManagerHybridauthItemRemoveProcessor';