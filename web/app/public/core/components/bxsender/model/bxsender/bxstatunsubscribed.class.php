<?php

class bxStatUnSubscribed extends xPDOSimpleObject
{
    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
        }
        return parent::save();
    }


    /**
     * Отписка от рассылки
     * @param $subscriber_id
     * @param $email
     */
    public function unSubscriber($subscriber_id, $email)
    {
        $criteria = array();
        $subscriber_id = (int)$subscriber_id;
        if (!empty($subscriber_id)) {
            $criteria = array(
                'id' => $subscriber_id
            );
        } else if (!empty($email)) {
            $criteria = array(
                'email' => $email
            );
        }
        /* @var bxSubscriber $Subscriber */
        if ($Subscriber = $this->xpdo->getObject('bxSubscriber', $criteria)) {
            $Subscriber->unSubscribed();
        }
    }
}