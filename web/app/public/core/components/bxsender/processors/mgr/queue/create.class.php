<?php
/**
 * Create an Item
 */
class bxQueueCreateProcessor extends modObjectCreateProcessor {
    /* @var bxQueue $object*/
    public $object;
    public $objectType = 'message';
    public $classKey = 'bxQueue';
    public $permission = 'create';

    public function beforeSave()
    {
        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->object->operation('update', array(
            'createdon' => time(),
            'state' => 'prepare',
            'user_id' => $this->modx->user->id,
        ));
        return true;
    }
}

return 'bxQueueCreateProcessor';