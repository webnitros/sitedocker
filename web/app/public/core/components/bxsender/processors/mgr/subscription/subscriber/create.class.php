<?php
include_once dirname(__FILE__) . '/trait.class.php';

/**
 * Create an Subscriber
 */
class bxSubscriberCreateProcessor extends modObjectCreateProcessor
{
    use bxSubscriberTrait;

    /* @var bxSubscriber $object */
    public $object;
    public $objectType = 'bxSubscriber';
    public $classKey = 'bxSubscriber';
    public $languageTopics = array('bxsender:subscription');
    public $permission = '';


    /**
     * @return bool
     */
    public function beforeSet()
    {

        $required = array('email');
        foreach ($required as $tmp) {
            if (!$this->getProperty($tmp)) {
                $this->addFieldError($tmp, $this->modx->lexicon('field_required'));
            }
        }

        if ($this->hasErrors()) {
            return $this->modx->lexicon('bxsender_subscriber_err_save');
        }

        /* @var modUser $User */
        $user_id = (int)$this->getProperty('user_id');
        if ($user_id) {
            if (!$User = $this->modx->getObject('modUser', $this->getProperty('user_id'))) {
                $this->addFieldError('user', $this->modx->lexicon('bxsender_subscriber_err_user_id'));
            }
        }

        $email = trim($this->getProperty('email'));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFieldError('email', $this->modx->lexicon('bxsender_subscriber_err_email'));
        }
        $this->setProperty('email', $email);


        $segments = $this->getProperty('segments');
        if (!is_array($segments)) {
            $segments = explode(',', $segments);
        }



        $segments = array_filter($segments);
        if (empty($segments)) {
            $this->addFieldError('segments', $this->modx->lexicon('bxsender_subscriber_err_segment'));
        }


        // Если подписка уже создана то добавляем её в выбранные сегменты
        if ($object = $this->modx->getObject($this->classKey, array('email' => $email))) {
            $this->object = $object;
        }

        $this->addSegment($this->object, $segments);
        return !$this->hasErrors();
    }


    /**
     * Abstract the saving of the object out to allow for transient and non-persistent object updating in derivative
     * classes
     * @return boolean
     */
    public function saveObject()
    {
        return $this->object->save();
    }

}

return 'bxSubscriberCreateProcessor';
