<?php
include_once dirname(__FILE__) . '/trait.class.php';
/**
 * Update an bxSubscriber
 */
class bxSubscriberUpdateProcessor extends modObjectUpdateProcessor
{
    use bxSubscriberTrait;


    /* @var bxSubscriber object*/
    public $object;
    public $objectType = 'bxSubscriber';
    public $classKey = 'bxSubscriber';
    public $languageTopics = array('bxsender');
    public $permission = 'edit_document';

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

        $email = $this->getProperty('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFieldError('email', $this->modx->lexicon('bxsender_subscriber_err_email'));
        }
        return !$this->hasErrors();
    }
}

return 'bxSubscriberUpdateProcessor';
