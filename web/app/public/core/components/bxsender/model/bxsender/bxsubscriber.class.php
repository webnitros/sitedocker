<?php

class bxSubscriber extends xPDOSimpleObject
{
    /* @var modUser $user */
    protected $user = null;

    /**
     * Loads User
     */
    public function loadUser($false = false)
    {
        if (!is_object($this->user) || !($this->user instanceof modUser)) {
            if (!$this->user = $this->getOne('User')) {
                $this->user = $false ? false : $this->xpdo->newObject('modUser');
            }
        }
        return $this->user;
    }

    /**
     * Проверка исключение подписки
     * @return bool
     */
    public function isUnSubscribed()
    {
        return (boolean)$this->xpdo->getCount('bxUnSubscribed', array('email' => $this->get('email')));
    }

    /**
     * Отписка
     * @return bool
     */
    public function addSubscribed($confirmed = false)
    {
        if ($confirmed) {
            $this->set('confirmed', 1);
        }
        $this->set('state', 'subscribe');
        return $this->save();
    }


    /**
     * Отписка
     * @return bool
     */
    public function unSubscribed()
    {
        $this->set('state', 'unsubscribed');
        return $this->save();
    }

    public function remove(array $ancestors = array())
    {
        $hash = $this->get('hash_activate_subscription');
        if (!empty($hash)) {
            // Отчищаем очередь от сообщений для подписчика
            /** @var modRegistry $registry */
            $registry = $this->xpdo->getService('registry', 'registry.modRegistry');
            $instance = $registry->getRegister('user', 'registry.modDbRegister');
            $instance->connect();
            $instance->subscribe('/bxsender/subscribe/' . $hash);
            $instance->read(array('poll_limit' => 1));
        }

        return parent::remove($ancestors);
    }

    /**
     * Добавление в список с усключенными
     * @return bool
     */
    public function removeUnSubscribed()
    {

        /* @var bxUnSubscribed $UnSubscribed */
        if ($this->isUnSubscribed()) {
            if ($object = $this->xpdo->getObject('bxUnSubscribed', array('email' => $this->get('email')))) {
                return $object->remove();
            }
        }
        return true;
    }

    /**
     * Добавление в список с усключенными
     * @return bool
     */
    public function addUnSubscribed()
    {
        /* @var bxUnSubscribed $UnSubscribed */
        if (!$this->isUnSubscribed()) {
            $UnSubscribed = $this->xpdo->newObject('bxUnSubscribed');
            $UnSubscribed->set('email', $this->get('email'));
            return $UnSubscribed->save();
        }
        return true;
    }

    /**
     * Обновление сегментов подписик
     * @param string $state
     * @param array $checkeds
     * @return bool
     */
    public function updateSegments($state = 'subscribe', $checkeds = array(), $save = true)
    {
        $state = trim($state);
        if ($state != 'subscribe' and $state != 'unsubscribed') {
            $state = 'subscribe';
        }
        $this->xpdo->log(modX::LOG_LEVEL_ERROR, "segment_id 333", '', __METHOD__, __FILE__, __LINE__);

        if ($segments = $this->getSubscriptionsSegment()) {
            foreach ($segments as $segment) {
                $segment_id = (int)$segment['id'];
                $checked = false;
                if ($state == 'subscribe') {
                    if (!empty($checkeds[$segment_id])) {
                        $checked = (boolean)$checkeds[$segment_id];
                    }
                }

                $criteria = array(
                    'subscriber_id' => $this->get('id'),
                    'segment_id' => $segment_id
                );

                if (empty($segment_id)) {
                    $checked = false;
                }
                if ($checked) {
                    if (!$Member = $this->xpdo->getObject('bxSubscriberMember', $criteria)) {
                        /* @var bxSubscriberMember $SubscriberMember */
                        $SubscriberMember = $this->xpdo->newObject('bxSubscriberMember');
                        $SubscriberMember->fromArray($criteria, '', true);
                        $this->addMany($SubscriberMember);
                    }
                } else {
                    if ($Member = $this->xpdo->getObject('bxSubscriberMember', $criteria)) {
                        $Member->remove();
                    }
                }
            }
        }

        // Сохраняем статус подписки
        if ($state == 'unsubscribed') {
            $this->set('state', 'unsubscribed');
        } else {
            $this->set('state', 'subscribe');
        }
        return $save ? $this->save() : true;
    }

    /**
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if ($this->isNew()) {
            if (empty($this->get('createdon'))) {
                $this->set('createdon', time());
            }
            $this->set('token', sha1(uniqid(sha1($this->get('email')), true)));
        } else {
            $this->set('updatedon', time());
        }


        switch ($this->get('state')) {
            case 'unsubscribed':
                // Отключение подписчика
                $this->set('active', 0);
                $this->addUnSubscribed();
                $this->updateSegments('unsubscribed', array(), false);
                break;
            case 'activate_subscription':
                $this->set('active', 0);
                break;
            default:
                $this->set('active', 1);
                $this->removeUnSubscribed();
                break;
        }
        return parent::save();
    }


    /**
     * Вернет активные сигмены и сегменты которые разрешены для показа на сайте.
     * @return bool|array
     */
    public function getSubscriptionsSegment()
    {
        $subscribers = null;
        $subscriber_id = $this->get('id');
        $q = $this->xpdo->newQuery('bxSegment');
        $q->select($this->xpdo->getSelectColumns('bxSegment', 'bxSegment', '', array('id', 'name', 'description')));
        $q->select("(SELECT COUNT(DISTINCT Subscriber.subscriber_id) FROM {$this->xpdo->getTableName('bxSubscriberMember')} as Subscriber WHERE Subscriber.segment_id = bxSegment.id AND Subscriber.subscriber_id = {$subscriber_id}) as checked");
        $q->where(array(
            'bxSegment.allow_subscription' => 1,
            'bxSegment.active' => 1,
        ));
        $q->sortby('bxSegment.rank', 'ASC');
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $subscribers[] = $row;
            }
        }
        return $subscribers;
    }

    /**
     * @return array
     */
    public function getTokenData()
    {
        return $this->get(array('token', 'email'));
    }

}