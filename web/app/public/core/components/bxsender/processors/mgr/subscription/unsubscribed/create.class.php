<?php

/**
 * Create an Subscriber
 */
class bxUnSubscribedCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'bxUnSubscribed';
    public $classKey = 'bxUnSubscribed';
    public $permission = '';
    public $languageTopics = array('bxsender:manager', 'bxsender:subscription');

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $subscriber_id = (int)$this->getProperty('subscriber_id');

        if (!$Subscriber = $this->modx->getObject('bxSubscriber', $subscriber_id)) {
            $this->addFieldError('subscriber_id', $this->modx->lexicon('bxsender_unsubscribed_subscriber_err_could_not_found'));
        } else {
            $email = $Subscriber->get('email');
            $this->setProperty('email', $email);
            $this->setProperty('createdon', time());

            if ($count = (boolean)$this->modx->getCount($this->classKey, array('email' => $email))) {
                $this->addFieldError('subscriber_id', $this->modx->lexicon('bxsender_unsubscribed_subscriber_err_email_unscriber'));
            }
        }

        return !$this->hasErrors();
    }
}

return 'bxUnSubscribedCreateProcessor';
