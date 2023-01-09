<?php
/**
 * Get an Mailing
 */
class bxMailingGetProcessor extends modObjectGetProcessor {
	public $objectType = 'bxMailing';
	public $classKey = 'bxMailing';
	public $languageTopics = array('bxsender:default');


    public function beforeOutput() {
        $this->object->set('send_user', true);
        $this->object->set('delete_after_sending', true);
    }
}

return 'bxMailingGetProcessor';