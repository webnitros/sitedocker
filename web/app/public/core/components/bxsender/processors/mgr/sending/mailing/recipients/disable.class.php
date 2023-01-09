<?php

class bxSegmentMemberRemoveProcessor extends modObjectRemoveProcessor
{
    /** @var bxSegment $object */
    public $object;
    public $classKey = 'bxMailingMember';
    public $languageTopics = array('bxsender');
    public $permission = 'mssetting_save';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        $this->object = $this->modx->getObject($this->classKey, $this->getProperties());
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType . '_err_nfs');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->object->fromArray($this->getProperties(), '', true, true);

        return true;
    }

}

return 'bxSegmentMemberRemoveProcessor';
